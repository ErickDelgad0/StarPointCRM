<?php
include "../../php/functions.php";
// convert all date to datetime cus 2038 is max year strtotime cant handle it
class Calendar {

    // Class variables
    private $active_year, $active_month, $active_day;
    private $events = [];
    private $pdo;
    private $uid;
    private $expanded_list;

    // Initialize the class variables
    public function __construct($date = null, $uid = 1, $expanded_list = false) {
        // The specified year
        $this->active_year = $date != null ? $this->format_date('Y', $date) : $this->format_date('Y');
        // The specified month
        $this->active_month = $date != null ? $this->format_date('m', $date) : $this->format_date('m');
        // The specified day
        $this->active_day = $date != null ? $this->format_date('d', $date) : $this->format_date('d');
        // Unique ID
        $this->uid = $uid;
        // Expanded list
        $this->expanded_list = $expanded_list;
    }

    // Connect to database function using the PDO interface
    public function connect_to_database() {
        try {
            // Use the pdo_connect_mysql function to establish a database connection
            $this->pdo = pdo_connect_mysql();
        } catch (PDOException $exception) {
            // If there is an error with the connection, stop the script and output the error.
            exit('Failed to connect to database using pdo_connect_mysql!');
        }
        // update event list
        $this->update_events();      
    }
    
    public function format_date($format, $date = null, $modify = null) {
        $date = $date != null ? new DateTime($date) : new DateTime();
        if ($modify != null) {
            $date->modify($modify);
        }
        return $date->format($format);
    }

    // Retrieve all the events from the database based on the "uid" column (Unique ID)
    public function update_events() {
        $stmt = $this->pdo->prepare('SELECT * FROM events WHERE uid = ? AND (recurring != "never" OR ((datestart >= ? OR dateend >= ?) AND datestart <= ?))');
        $stmt->execute([ 
            $this->uid, 
            $this->format_date('Y-m-d H:i:s', $this->active_year . '-' . $this->active_month . '-01'), 
            $this->format_date('Y-m-d H:i:s', $this->active_year . '-' . $this->active_month . '-01'), 
            $this->format_date('Y-m-d H:i:s', $this->active_year . '-' . $this->active_month . '-31') 
        ]);
        $this->events = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->add_recurring_events();
    }

    // Update the "events" array and add recurring events
    public function add_recurring_events() {
        foreach ($this->events as $event) {
            if ($event['recurring'] == 'daily') {
                $earlier = new DateTime($event['datestart']);
                if ($this->format_date('Y-m', $event['datestart']) == $this->active_year . '-' . $this->active_month) {
                    $later = new DateTime($event['datestart']);
                    $abs_diff = $later->diff($earlier)->format('%a')+1;
                } else {
                    $later = new DateTime($this->active_year . '-' . $this->active_month . '-01');
                    $abs_diff = $later->diff($earlier)->format('%a');
                }
                for ($i = $abs_diff; $i < ($abs_diff+31); $i++) {
                    $new_event = $event;
                    $new_event['datestart'] = $this->format_date('Y-m-d H:i:s', $event['datestart'], '+' . $i . ' day');
                    $new_event['dateend'] = $this->format_date('Y-m-d H:i:s', $event['dateend'], '+' . $i . ' day');
                    $this->events[] = $new_event;
                }
            }
            if ($event['recurring'] == 'weekly' && $this->format_date('Y-m', $event['datestart']) <= $this->format_date('Y-m', $this->active_year . '-' . $this->active_month)) {
                $earlier = new DateTime($event['datestart']);
                $later = new DateTime($this->active_year . '-' . $this->active_month . '-01');
                $abs_diff = $later->diff($earlier)->format('%a');
                for ($i = ($abs_diff-31); $i < ($abs_diff+31); $i++) {
                    if ($i % 7 == 0 && $i > 0) {
                        $new_event = $event;
                        $new_event['datestart'] = $this->format_date('Y-m-d H:i:s', $event['datestart'], '+' . $i . ' day');
                        $new_event['dateend'] = $this->format_date('Y-m-d H:i:s', $event['dateend'], '+' . $i . ' day');
                        $this->events[] = $new_event;
                    }
                }
            }
            if ($event['recurring'] == 'monthly') {
                $new_event = $event;
                $earlier = new DateTime($event['datestart']);
                $later = new DateTime($event['dateend']);
                $abs_diff = $later->diff($earlier)->format('%a');
                if ($this->format_date('Y-m', $this->active_year . '-' . $this->active_month) > $this->format_date('Y-m', $event['datestart'])) {
                    $new_event['datestart'] = $this->active_year . '-' . $this->active_month . '-' . $this->format_date('d H:i:s', $event['datestart']);
                    $new_event['dateend'] = $this->format_date('Y-m-d H:i:s', $new_event['datestart'], '+' . $abs_diff . ' day');
                    $this->events[] = $new_event;
                    if ($this->format_date('Y-m', $new_event['dateend']) >= $this->format_date('Y-m', $this->active_year . '-' . $this->active_month)) {
                        $new_event['datestart'] = $this->format_date('Y-m-d H:i:s', $new_event['datestart'], '-1 month');
                        $new_event['dateend'] = $this->format_date('Y-m-d H:i:s', $new_event['datestart'], '+' . $abs_diff . ' day');
                        // Skip the first month, don't want duplicate events
                        if ($this->format_date('Y-m', $event['datestart'], '+1 month') != $this->format_date('Y-m', $this->active_year . '-' . $this->active_month)) {
                            $this->events[] = $new_event;
                        }
                    }
                }
            }
            if ($event['recurring'] == 'yearly') {
                $new_event = $event;
                if ($this->active_year > $this->format_date('Y', $event['datestart'])) {
                    $new_event['datestart'] = $this->active_year . '-' . $this->format_date('m-d H:i:s', $event['datestart']);
                    $new_event['dateend'] = $this->active_year . '-' . $this->format_date('m-d H:i:s', $event['dateend']);
                    $this->events[] = $new_event;
                }
            }
        }
    }

    // Add event to the database and update the "events" array
    public function add_event($title, $description, $datestart, $dateend, $color = '', $recurring = 'never', $file = null) {
        $photo_url = '';
        // Check if the file is not empty
        if ($file != null && !empty($file['tmp_name'])) {
            // Check if the file is an image
            if (getimagesize($file['tmp_name']) !== false) {
                // Generate a unique name for the image
                $photo_url = upload_directory . md5(uniqid()) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
                // Move the image to the uploads folder
                move_uploaded_file($file['tmp_name'], $photo_url);
            }
        }
        // Insert into database
        $stmt = $this->pdo->prepare('INSERT INTO events (title, description, color, datestart, dateend, uid, recurring, photo_url) VALUES (?,?,?,?,?,?,?,?)');
        $stmt->execute([ $title, $description, $color, $datestart, $dateend, $this->uid, $recurring, $photo_url ]);
        // Retrieve the ID
        $id = $this->pdo->lastInsertId();
        // Add to the list of events
        $this->events[] = [
            'id' => $id,
            'title' => $title,
            'description' => $description,
            'color' => $color,
            'datestart' => $datestart,
            'dateend' => $dateend,
            'uid' => $this->uid,
            'recurring' => $recurring,
            'photo_url' => $photo_url
        ];
        return true;    
    }

    // Update event function
    public function update_event($id, $title, $description, $datestart, $dateend, $color = '', $recurring = 'never', $file = null) {
        $photo_url = '';
        // Check if the event exists
        $event_exists = false;
        // Iterate all the events
        foreach ($this->events as &$event) {
            // Compare event ID with the specified ID
            if ($event['id'] == $id) {
                // Event exists... Update the event
                $event_exists = true;
                $event['title'] = $title;
                $event['description'] = $description;
                $event['datestart'] = $datestart;
                $event['dateend'] = $dateend;
                $event['color'] = $color;
                $event['recurring'] = $recurring;
                // Check if the file is not empty
                if ($file != null && !empty($file['tmp_name'])) {
                    // Check if the file is an image
                    if (getimagesize($file['tmp_name']) !== false) {
                        // Generate a unique name for the image
                        $photo_url = upload_directory . md5(uniqid()) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
                        // Move the image to the uploads folder
                        move_uploaded_file($file['tmp_name'], $photo_url);
                        // Update event variable
                        $event['photo_url'] = $photo_url;
                    }
                } else {
                    // Update photo URL variable
                    $photo_url = $event['photo_url'];
                }
            }
        }
        if (!$event_exists) {
            // Event doesn't exists! Return false
            return false;
        }
        // Update the event in the database
        $stmt = $this->pdo->prepare('UPDATE events SET title = ?, description = ?, color = ?, datestart = ?, dateend = ?, recurring = ?, photo_url = ? WHERE id = ?');
        $stmt->execute([ $title, $description, $color, $datestart, $dateend, $recurring, $photo_url, $id ]);
        return true;
    }

    public function update_lead_on_event_change($eventId) {
        // First, retrieve the lead_id associated with this event
        $stmt = $this->pdo->prepare('SELECT lead_id, description, datestart FROM events WHERE id = ?');
        $stmt->execute([$eventId]);
        $event = $stmt->fetch();
    
        if ($event && $event['lead_id']) {
            // If a corresponding lead exists, update the Leads table
            $stmtLeads = $this->pdo->prepare('UPDATE Leads SET notes = ?, recontact_date = ? WHERE id = ?');
            $stmtLeads->execute([$event['description'], $event['datestart'], $event['lead_id']]);
            return true;
        }
    
        // If no corresponding lead, or lead_id is not set, return false
        return false;
    }
    

    // Delete event function
    public function delete_event($id) {
        // Check if the event exists
        $event_exists = false;
        // Iterate all the events
        foreach ($this->events as &$event) {
            if ($event['id'] == $id) {
                // Event exists, delete the event
                $event_exists = true;
                unset($event);
            }
        }
        if (!$event_exists) {
            // Event doesn't exists! Return false
            return false;
        }
        // Delete the event from the database
        $stmt = $this->pdo->prepare('DELETE FROM events WHERE id = ?');
        $stmt->execute([ $id ]);
        return true;    
    }

    // Function that returns all events by a specified date
    public function list_events_by_date($date) {
        // New events list
        $events_list = [];
        // Iterate all the events
        foreach ($this->events as $event) {
            // Determine the event date
            $event_date = $this->format_date('y-m-d', $date);
            // Compare the event "dateend" and "dateend" with the date above
            if ($event_date >= $this->format_date('y-m-d', $event['datestart']) && $event_date <= $this->format_date('y-m-d', $event['dateend'])) {
                // Add event to new array
                $events_list[] = $event;
            }
        }
        // Sort the new array by the "datestart" column
        array_multisort(array_column($events_list, 'datestart'), SORT_ASC, $events_list);
        // Return array
        return $events_list;
    }

    // Function that returns all the events by a specified date in HTML format
    public function list_events_by_date_html($date) {
        // Retrieve events array
        $events_list = $this->list_events_by_date($date);
        // HTML variable
        $html = '';
        // Make events array is not empty
        if ($events_list) {
            // Add the events container
            $html .= '<div class="events" data-disable-event-management="' . disable_event_management . '">';
            // Iterate the events array
            foreach ($events_list as $event) {
                // Determine the specified date
                $event_date = $this->format_date('y-m-d', $date);
                // Event template
                $html .= '<div class="event" data-id="' . $event['id'] . '" data-title="' . htmlspecialchars($event['title'], ENT_QUOTES) . '" data-start="' . $event['datestart'] . '" data-end="' . $event['dateend'] . '" data-color="' . $event['color'] . '" data-date="' . $this->format_date('j F Y', $date) . '" data-recurring="' . $event['recurring'] . '" data-photo-url="' . $event['photo_url'] . '">';
                $html .= '<div class="details"><i class="date" style="border-right:3px solid ' . $event['color'] . '">' . ($event_date == $this->format_date('y-m-d', $event['datestart']) ? $this->format_date('H:ia', $event['datestart']) : 'Ongoing') . '</i> <div class="title">' . htmlspecialchars($event['title'], ENT_QUOTES) . '</div> ' . (!disable_event_management ? '<i class="edit fas fa-pen fa-xs" title="Edit Event"></i> <i class="delete fas fa-trash fa-xs" title="Delete Event"></i>' : '') . '</div>';
                if (!empty($event['description'])) {
                    $html .= '<p class="description">' . nl2br(htmlspecialchars($event['description'], ENT_QUOTES)) . '</p>';
                }
                $html .= '</div>';
            }
            $html .= '</div>';
        }
        // Return the events HTML template
        return $html;
    }

    // Expanded view function
    public function expanded_view() {
        // HTML variable
        $html = '<div class="calendar-expanded-view">';
        // Add heading
        $html .= '<h3 class="heading">Events for ' . $this->format_date('F', $this->active_year . '-' . $this->active_month) . '</h3>';
        // Retrieve events array
        $events = $this->events;
        // Sort the new array by the "datestart" column
        array_multisort(array_column($events, 'datestart'), SORT_ASC, $events);
        // Number of events
        $i = 0;
        // Iterate all the events for the selected month
        foreach ($events as $event) {
            // Event start date
            $event_date = $this->format_date('y-m', $event['datestart']);
            $current_month = $this->format_date('y-m', $this->active_year . '-' . $this->active_month);
            if ($event_date == $current_month) {
                // Event template
                $html .= '<div class="event">';
                $html .= '<div class="date"><div class="day" style="border-right:4px solid ' . $event['color'] . '">' . $this->format_date('d', $event['datestart']) . '</div><div class="month" style="border-right:4px solid ' . $event['color'] . '">' . strtoupper($this->format_date('M', $event['datestart'])) . '</div></div>';
                if ($event['photo_url'] != '') {
                    $html .= '<div class="photo"><img src="' . $event['photo_url'] . '" width="100" height="100" alt=""></div>';
                }
                $html .= '<div class="con">';
                $html .= '<h3 class="title">' . htmlspecialchars($event['title'], ENT_QUOTES) . '</h3>';
                if (!empty($event['description'])) {
                    $html .= '<p class="description">' . nl2br(htmlspecialchars($event['description'], ENT_QUOTES)) . '</p>';
                }
                $html .= '<span class="time"><i class="fa-regular fa-clock fa-sm"></i>From ' . $this->format_date('G:ia', $event['datestart']) . ' to ' . $this->format_date('G:ia', $event['dateend']) . ' on ' . $this->format_date('jS F', $event['dateend']) . '</span>';
                $html .= '</div>';
                $html .= '</div>';
                // Increment variable
                $i++;
            }
        }
        $html .= '</div>';
        // Return the events HTML template
        return $i ? $html : '';
    }

    // Determine event position
    private function determine_event_pos($event, $pos = 0) {
        // If the event position is already declared, return the existing position
        if (isset($event['pos'])) return $event['pos'];
        // Iterate the events array
        foreach ($this->events as $e) {
            // Determine the event dates
            $event_date_end = $this->format_date('y-m-d', $e['dateend']);
            $current_event_date_start = $this->format_date('y-m-d', $event['datestart']);
            // Compare event dates
            if ($current_event_date_start <= $event_date_end) {
                // Does the event position already exist?
                if (isset($e['pos']) && $e['pos'] == $pos) {
                    // If so, increment the position
                    return $this->determine_event_pos($event, $pos+1);
                }
            }
        }
        // Return the new position
        return $pos;
    }

    // Calendar template
    public function __toString() {
        // Determine the number of days in the active month based on the active_* variables
        $num_days = $this->format_date('t', $this->active_day . '-' . $this->active_month . '-' . $this->active_year);
        // Determine the number of days last month
        $num_days_last_month = $this->format_date('j', $this->active_day . '-' . $this->active_month . '-' . $this->active_year, 'last day of previous month');
        // Do not modify the $days variable as it is used to determine the first day of the week
        // $days = [0 => 'Sun', 1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat'];
        $days = [0 => 'Mon', 1 => 'Tue', 2 => 'Wed', 3 => 'Thu', 4 => 'Fri', 5 => 'Sat', 6 => 'Sun'];
        // If you need to translate the day names, you can change the values below and not the values in the above array.
        $translated_days = [0 => 'Mon', 1 => 'Tue', 2 => 'Wed', 3 => 'Thu', 4 => 'Fri', 5 => 'Sat', 6 => 'Sun'];
        $translated_days_mini = [0 => 'M', 1 => 'T', 2 => 'W', 3 => 'T', 4 => 'F', 5 => 'S', 6 => 'S'];
        // Determine the first day of the week
        $first_day_of_week = array_search($this->format_date('D', $this->active_year . '-' . $this->active_month . '-1'), $days);
        // Template code
        $html = '<div class="calendar">';
        $html .= '<div class="header">';
        $html .= '<div class="month-year">';
        $html .= '<a href="#" class="current">' . $this->format_date('F Y', $this->active_year . '-' . $this->active_month . '-' . $this->active_day) . '</a>';
        $html .= '<a href="#" class="today">today</a>';
        $html .= '<a href="#" class="refresh"><i class="fa-solid fa-rotate fa-xs"></i></a>';
        $html .= '<a href="#" class="prev" data-date="' . $this->format_date('Y-m-01', $this->active_year . '-' . $this->active_month . '-' . $this->active_day, '-1 month') . '"><i class="fa-solid fa-angle-left fa-xs"></i></a>';
        $html .= '<a href="#" class="next" data-date="' . $this->format_date('Y-m-01', $this->active_year . '-' . $this->active_month . '-' . $this->active_day, '+1 month') . '"><i class="fa-solid fa-angle-right fa-xs"></i></a>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="days">';
        // Populate the translated days
        foreach ($translated_days as $k => $day) {
            $html .= '
                <div class="day_name">
                    <span class="size-normal">' . $day . '</span>
                    <span class="size-mini">' . $translated_days_mini[$k] . '</span>
                </div>
            ';
        }
        // Add the ignore containers to the start of the calendar
        for ($i = $first_day_of_week; $i > 0; $i--) {
            $html .= '
                <div class="day_num ignore">
                    ' . ($num_days_last_month-$i+1) . '
                </div>
            ';
        }
        // Iterate the number of days
        for ($i = 1; $i <= $num_days; $i++) {
            // Set the selected day
            $selected = '';
            // Current day
            $current_day = $this->format_date('Y-m-d', $this->active_year . '-' . $this->active_month . '-' . $i);
            // Highlight the current day
            if ($current_day == $this->format_date('Y-m-d')) {
                $selected = ' selected';
            }
            // Day container
            $html .= '<div class="day_num' . $selected . '" data-date="' . $current_day . '" data-label="' . $this->format_date('j F Y', $this->active_year . '-' . $this->active_month . '-' . $i) . '">';
            // Day number
            $html .= '<span>' . $i . '</span>';
            // All the below event templates will appear in the following array; based on the event position
            $event_template_array = [];
            // Does the day have events
            $has_events = false;
            // The max event position for the current day
            $max_pos = 0;
            // Iterate all the events
            foreach ($this->events as &$event) {
                // The event date
                $event_date = $this->format_date('y-m-d', $event['datestart']);
                // Determine the event dates
                $event_start_date = new DateTime($event_date);
                $event_start_date->setTime(0, 0, 0);
                $event_end_date = new DateTime($event['dateend']);
                $event_end_date->setTime(0, 0, 0);
                // Only populate the event template if the event is within the current month
                if ($this->format_date('y-m-d', $event['dateend']) > $this->format_date('y-m-d', $this->active_year . '-' . $this->active_month . '-31')) {
                    // Event exceeds the current month, so update it the beginning of the next month
                    $event_end_date = new DateTime($this->active_year . '-' . $this->active_month . '-01 +1 month');
                }
                // The number of days the event lasts
                $event_num_days = $event_start_date->diff($event_end_date)->format('%r%a');
                // Iterate the number of event days
                for ($d = 0; $d <= $event_num_days; $d++) {
                    // Determine the active date
                    $active_date = $this->format_date('y-m-d', $this->active_year . '-' . $this->active_month . '-' . $i . ' -' . $d . ' day');
                    // Compare dates
                    if ($active_date == $event_date) {
                        // Determine the event position
                        $event['pos'] = $this->determine_event_pos($event);
                        // The current CSS class
                        $current = '';
                        if ($d == 0 && $event_num_days > 0) {
                            $current = ' event-start';
                        }
                        if ($d > 0 && $d < $event_num_days) {
                            $current = ' event-ongoing';
                        }
                        if ($d > 0 && $event_num_days == $d) {
                            $current = ' event-end';
                        }
                        // The event template                      
                        $event_template_array[$event['pos']] = '<div data-pos="' . $event['pos'] . '" class="event' . $current . '"' . ($event['color'] ? ' style="order:' . $event['pos'] . ';background-color:' . htmlspecialchars($event['color'], ENT_QUOTES) . '"' : '') . '>';
                        // Add the event title but only if it's the start of the event
                        if ($current == ' event-start' || $current == '' || ($current == ' event-ongoing' && ($i+$first_day_of_week) % 7 == 1) || ($current == ' event-end' && ($i+$first_day_of_week) % 7 == 1) || $i == 1) {
                            $event_template_array[$event['pos']] .= '<p class="size-normal">' . htmlspecialchars($event['title'], ENT_QUOTES) . '</p>';
                        }
                        $event_template_array[$event['pos']] .= '</div>';
                        // Event exists, so update the variable
                        $has_events = true;
                        // Update the maximum position variable
                        $max_pos = $event['pos'] > $max_pos ? $event['pos'] : $max_pos;
                    }
                }
            }
            // Check if the day has events
            if ($has_events) { 
                // Add all event templates to the main template
                for ($p = 0; $p <= $max_pos; $p++) {
                    if (isset($event_template_array[$p])) {
                        $html .= $event_template_array[$p];
                    } else {
                        $html .= '<div class="event" style="order:' . $p . '"></div>';
                    }
               }
            }
            $html .= '</div>';
        }
        // Add the ignore containers to the end of the calendar
        for ($i = 1; $i <= (42-$num_days-max($first_day_of_week, 0)); $i++) {
            $html .= '
                <div class="day_num ignore">
                    ' . $i . '
                </div>
            ';
        }
        $html .= '</div>';
        $html .= '</div>';
        // Expanded list view
        if ($this->expanded_list) {
            $html .= $this->expanded_view();
        }
        // Return the template code
        return $html;
    }

}
?>