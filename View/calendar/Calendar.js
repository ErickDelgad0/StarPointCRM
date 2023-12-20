'use strict';
class Calendar {

    constructor(options) {
        // Declare the default options
        let defaults = {
            uid: 1,
            container: document.querySelector('.calendar-container'),
            php_file_url: 'Calendar.php',
            current_date: new Date().toISOString().substring(0, 10),
            size: 'auto',
            expanded_list: false,
            event_management: true
        };
        // Declare the calendar options
        this.options = Object.assign(defaults, options);
        // Modal is not currently open
        this.isModalOpen = false;
        // Fetch the calendar
        this.fetchCalendar();
    }

    // Fetch the calendar using AJAX
    fetchCalendar() {
        // Add the loading state
        this.addLoaderIcon();
        // Fetch the calendar
        fetch(this.ajaxUrl, { cache: 'no-store' }).then(response => response.text()).then(data => {
            // Load complete
            // Ouput the response
            this.container.innerHTML = data;
            // Determine the calendar size
            this.container.querySelector('.calendar').classList.remove('normal', 'mini', 'auto');
            this.container.querySelector('.calendar').classList.add(this.size);
            // Determine the expanded view size
            if (this.container.querySelector('.calendar-expanded-view')) {
                this.container.querySelector('.calendar-expanded-view').classList.remove('normal', 'mini', 'auto');
                this.container.querySelector('.calendar-expanded-view').classList.add(this.size);
            }
            // Trigger the event handlers
            this._eventHandlers();
            // Remove the loading state
            this.removeLoaderIcon();
        });
    }

    // Determine the AJAX URL
    get ajaxUrl() {
        let url = `${this.phpFileUrl}${this.phpFileUrl.includes('?') ? '&' : '?'}uid=${this.uid}`;
        url += 'current_date' in this.options ? `&current_date=${this.currentDate}` : '';
        url += 'size' in this.options ? `&size=${this.size}` : '';
        url += this.expandedList ? `&expanded_list=${this.expandedList}` : '';
        return url;       
    }

    // Get: Unique ID
    get uid() {
        return this.options.uid;
    }

    // Set: Unique ID
    set uid(value) {
        this.options.uid = value;
    }

    // Get: PHP calendar file URL
    get phpFileUrl() {
        return this.options.php_file_url;
    }

    // Set: PHP calendar file URL
    set phpFileUrl(value) {
        this.options.php_file_url = value;
    }

    // Get: HTML DOM calendar container
    get container() {
        return this.options.container;
    }

    // Set: HTML DOM calendar container
    set container(value) {
        this.options.container = value;
    }

    // Get: current calendar date
    get currentDate() {
        return this.options.current_date;
    }

    // Set: current calendar date
    set currentDate(value) {
        this.options.current_date = value;
    }

    // Get: calendar size (normal|mini)
    get size() {
        return this.options.size;
    }

    // Set: calendar size (normal|mini)
    set size(value) {
        this.options.size = value;
    }

    // Get: expanded list (true|false)
    get expandedList() {
        return this.options.expanded_list;
    }

    // Set: expanded list (true|false)
    set expandedList(value) {
        this.options.expanded_list = value;
    }

    // Function that will open the date select modal
    openDateSelectModal(x, y, currentDate) {
        // If there is already a modal open, return false
        if (this.isModalOpen) {
            return false;
        }
        // Update the isModalOpen var
        this.isModalOpen = true;
        // Update the calendar CSS opacity property
        document.querySelector('.calendar').style.opacity = '.3';
        // Add the date select template modal to the HTML document
        document.body.insertAdjacentHTML('beforeend', `
            <div class="calendar-date-modal">
                <div class="calendar-event-modal-header">
                    <h5>Select Date</h5>
                    <a href="#" class="close"><i class="fa-solid fa-xmark"></i></a>
                </div>
                <div class="calendar-event-modal-content date-select">
                    <h5>Month</h5>
                    <h5>Year</h5>
                    <div class="months"></div>
                    <div class="years"></div>
                </div>
                <div class="calendar-event-modal-footer">
                    <a href="#" class="save">Save</a>
                    <a href="#" class="close">Close</a>
                </div>
            </div>
        `);
        // Select the above modal
        let modalElement = document.querySelector('.calendar-date-modal');
        // Retrieve the modal rect properties
        let rect = modalElement.getBoundingClientRect();
        // Position the modal (center center)
        modalElement.style.top = parseInt(y-(rect.height/2)) + 'px';
        modalElement.style.left = parseInt(x-(rect.width/2)) + 'px';
        // Determine the current month
        let currentMonth = new Date(currentDate).getMonth() + 1;
        // Iterate every month in the year and add the month to the modal
        for (let month = 1; month <= 12; month++) {
            modalElement.querySelector('.months').insertAdjacentHTML('beforeend', `
                <div class="month${month==currentMonth?' active':''}">${month}</div>
            `);
        }
        // Start year; deduct 40 years from the current year
        let startYear = new Date().getFullYear()-40;
        // End year; add 40 years to the current year
        let endYear = new Date().getFullYear()+40;
        // Current year
        let currentYear = new Date(currentDate).getFullYear();
        // Iterate from the start year to the end year and add the year to the modal
        for (let year = startYear; year <= endYear; year++) {
            modalElement.querySelector('.years').insertAdjacentHTML('beforeend', `
                <div class="year${year==currentYear?' active':''}">${year}</div>
            `);
        }
        // Iterate all months in the modal and add the onclick event, which will add the "active" css class to the corresponding month
        modalElement.querySelectorAll('.month').forEach(element => {
            element.onclick = () => {
                modalElement.querySelectorAll('.month').forEach(element => element.classList.remove('active'));
                element.classList.add('active');
            };
        });
        // Iterate all years in the modal and add the onclick event, which will add the "active" css class to the corresponding year
        modalElement.querySelectorAll('.year').forEach(element => {
            element.onclick = () => {
                modalElement.querySelectorAll('.year').forEach(element => element.classList.remove('active'));
                element.classList.add('active');
            };
        });
        // Position the modal scroll bars
        modalElement.querySelector('.month.active').parentNode.scrollTop = modalElement.querySelector('.month.active').offsetTop - 100;
        modalElement.querySelector('.year.active').parentNode.scrollTop = modalElement.querySelector('.year.active').offsetTop - 100;
        // Save the selected month and year
        modalElement.querySelector('.save').onclick = event => {
            event.preventDefault();
            // Update the current date
            this.currentDate = modalElement.querySelector('.year.active').innerHTML + '-' + modalElement.querySelector('.month.active').innerHTML + '-01';
            // Remove the modal
            modalElement.remove();
            // Update the calendar CSS opacity property
            document.querySelector('.calendar').style.opacity = '1';
            // Fetch the calendar
            this.fetchCalendar();
            // Modal is no longer open
            this.isModalOpen = false;
        };
        this.closeEventHandler(modalElement);
    }

    // Function that will open the event list modal
    openEventModal(x, y, startDate, endDate, eventsList, dateLabel) {
        // If there is already a modal open, return false
        if (this.isModalOpen) {
            return false;
        }
        // Update the isModalOpen var
        this.isModalOpen = true;
        // Update the calendar CSS opacity property
        document.querySelector('.calendar').style.opacity = '.3';
        // Add the date select template modal to the HTML document
        document.body.insertAdjacentHTML('beforeend', `
            <div class="calendar-event-modal">
                <div class="calendar-event-modal-header">
                    <h5>${dateLabel}</h5>
                    <a href="#" class="close"><i class="fa-solid fa-xmark"></i></a>
                </div>
                <div class="calendar-event-modal-content">
                ${eventsList}
                </div>
                <div class="calendar-event-modal-footer">
                    <a href="#" class="close">Close</a>
                </div>
            </div>
        `);
        // Select the above modal
        let modalElement = document.querySelector('.calendar-event-modal');
        // Retrieve the modal rect properties
        let rect = modalElement.getBoundingClientRect();
        // Position the modal (center center)
        modalElement.style.top = parseInt(y-(rect.height/2)) + 'px';
        modalElement.style.left = parseInt(x-(rect.width/2)) + 'px';  
        // Retrieve the calendar rect properties
        let calendar_rect = document.querySelector('.calendar').getBoundingClientRect();
        let calendar_x = (calendar_rect.width / 2) + calendar_rect.x;
        let calendar_y = (calendar_rect.height / 2) + calendar_rect.y;
        // Check if event management is enabled
        if (modalElement.querySelector('.events').dataset.disableEventManagement) {
            this.options.event_management = false;
        }
        // Check if event management is enabled
        if (this.options.event_management) {
            // Iterate all events   
            modalElement.querySelectorAll('.events .event').forEach(element => {
                // Edit button onclick event
                element.querySelector('.edit').onclick = event => {
                    event.preventDefault();
                    // Remove the current modal
                    modalElement.remove();
                    // Modal is no longer open
                    this.isModalOpen = false;
                    // Edit object
                    let editObj = {
                        id: element.dataset.id,
                        title: element.dataset.title,
                        datestart: element.dataset.start,
                        dateend: element.dataset.end,
                        color: element.dataset.color,
                        description: element.querySelector('.description') ? element.querySelector('.description').innerHTML : '',
                        recurring: element.dataset.recurring,
                        photo_url: element.dataset.photo_url
                    };
                    // Open the add event modal
                    this.openAddEventModal(calendar_x, calendar_y, startDate, endDate, editObj);               
                };
                // Delete button onclick event
                element.querySelector('.delete').onclick = event => {
                    event.preventDefault();
                    // Remove the current modal
                    modalElement.remove();
                    // Modal is no longer open
                    this.isModalOpen = false;
                    // Open the delete event modal
                    this.openDeleteEventModal(calendar_x, calendar_y, element.dataset.id);               
                };
            });
            // Add the add event button to the modal footer
            modalElement.querySelector('.calendar-event-modal-footer').insertAdjacentHTML('afterbegin', '<a href="#" class="add_event">Add Event</a>');
            // Add event button onclick event
            modalElement.querySelector('.add_event').onclick = event => {
                event.preventDefault();
                // Remove the current modal
                modalElement.remove();
                // Modal is no longer open
                this.isModalOpen = false;
                // Open the add event modal
                this.openAddEventModal(calendar_x, calendar_y, startDate, endDate);
            };  
        }
        this.closeEventHandler(modalElement);
    }

    // Function that will open the add event modal 
    openAddEventModal(x, y, startDate, endDate, edit) {
        // If there is already a modal open, return false
        if (this.isModalOpen || !this.options.event_management) {
            return false;
        }
        // Update the isModalOpen var
        this.isModalOpen = true;
        // Update the calendar CSS opacity property
        document.querySelector('.calendar').style.opacity = '.3';
        // Create the date variables
        let startDateStr, endDateStr, t;
        // If editing an event
        if (edit) {
            // Update the start date string
            t = edit.datestart.split(/[- :]/);
            startDateStr = new Date(Date.UTC(t[0], t[1]-1, t[2], t[3], t[4], t[5])).toISOString();
        } else {
            startDateStr = new Date(startDate).toISOString();
        }
        if (edit) {
            // Update the end date string
            t = edit.dateend.split(/[- :]/);
            endDateStr = new Date(Date.UTC(t[0], t[1]-1, t[2], t[3], t[4], t[5])).toISOString();           
        } else {
            endDateStr = new Date(endDate).toISOString();
        }
        startDateStr = startDateStr.substring(0,startDateStr.length-1);
        endDateStr = endDateStr.substring(0,endDateStr.length-1);
        // Add the add event modal template to the HTML document
        document.body.insertAdjacentHTML('beforeend', `
            <div class="calendar-add-event-modal">
                <div class="calendar-event-modal-header">
                    <h5>${edit ? 'Update' : 'Add'} Event</h5>
                    <a href="#" class="close"><i class="fa-solid fa-xmark"></i></a>
                </div>
                <div class="calendar-event-modal-content">
                    <form>

                        <label for="title"><span class="required">*</span> Title</label>
                        <input id="title" name="title" type="text" placeholder="Title" value="${edit ? edit.title : ''}">

                        <label for="description">Description</label>
                        <textarea id="description" name="description" placeholder="Description">${edit ? edit.description : ''}</textarea>

                        <div class="wrapper">
                            <div class="column">
                                <label for="startdate"><span class="required">*</span> Start Date</label>
                                <input id="startdate" name="startdate" type="datetime-local" value="${startDateStr}">
                            </div>
                            <div class="column">
                                <label for="enddate"><span class="required">*</span> End Date</label>
                                <input id="enddate" name="enddate" type="datetime-local" value="${endDateStr}">
                            </div>
                        </div>

                        <div class="wrapper">
                            <div class="column">
                                <label for="recurring">Recurring</label>
                                <select id="recurring" name="recurring">
                                    <option value="never"${edit && edit.recurring == 'never' ? ' selected' : ''}>Never</option>
                                    <option value="daily"${edit && edit.recurring == 'daily' ? ' selected' : ''}>Daily</option>
                                    <option value="weekly"${edit && edit.recurring == 'weekly' ? ' selected' : ''}>Weekly</option>
                                    <option value="monthly"${edit && edit.recurring == 'monthly' ? ' selected' : ''}>Monthly</option>
                                    <option value="yearly"${edit && edit.recurring == 'yearly' ? ' selected' : ''}>Yearly</option>
                                </select>
                            </div>
                            <div class="column">
                                <label for="color">Color</label>
                                <input id="color" name="color" type="color" placeholder="Color" value="${edit ? edit.color : '#5373ae'}" list="presetColors">
        
                                <datalist id="presetColors">
                                    <option>#5373ae</option>
                                    <option>#ae5353</option>
                                    <option>#9153ae</option>
                                    <option>#53ae6d</option>
                                    <option>#ae8653</option>
                                </datalist>
                                <input type="hidden" name="eventid" value="${edit ? edit.id : ''}"> 
                            </div>
                        </div>

                        <label for="photo">Photo</label>
                        <input id="photo" name="photo" type="file" placeholder="Photo">

                        <span id="msg"></span>  

                    </form>
                </div>
                <div class="calendar-event-modal-footer">
                    <a href="#" class="add_event">${edit ? 'Update' : 'Add'} Event</a>
                    <a href="#" class="close">Cancel</a>
                </div>
            </div>
        `);
        // Select the modal element
        let modalElement = document.querySelector('.calendar-add-event-modal');
        // Retrieve the modal rect properties
        let rect = modalElement.getBoundingClientRect();
        // Position the modal (center center)
        modalElement.style.top = parseInt(y-(rect.height/2)) + 'px';
        modalElement.style.left = parseInt(x-(rect.width/2)) + 'px';   
        // Add event button onclick event
        modalElement.querySelector('.add_event').onclick = event => {
            event.preventDefault();
            // Disable the button
            modalElement.querySelector('.add_event').disabled = true;
            // Use AJAX to add a new event to the calendar
            fetch(this.ajaxUrl, { cache: 'no-store', method: 'POST', body: new FormData(modalElement.querySelector('form')) }).then(response => response.text()).then(data => {
                // Check if the response us "success"
                if (data.includes('success')) {
                    // Remove the modal
                    modalElement.remove();
                    // Fetch the calendar
                    this.fetchCalendar();
                    // Modal is no longer open
                    this.isModalOpen = false;  
                } else {
                    // Something went wrong... output the errors
                    modalElement.querySelector('#msg').innerHTML = data;
                    // Enable the button
                    modalElement.querySelector('.add_event').disabled = false;
                }
            });
        };
        this.closeEventHandler(modalElement);     
    }

    // Function that will open the delete event modal
    openDeleteEventModal(x, y, id) {
        // If there is already a modal open, return false
        if (this.isModalOpen || !this.options.event_management) {
            return false;
        }
        // Update the isModalOpen var
        this.isModalOpen = true;
        // Update the calendar CSS opacity property
        document.querySelector('.calendar').style.opacity = '.3';
        // Add the delete event modal template to the HTML document
        document.body.insertAdjacentHTML('beforeend', `
            <div class="calendar-delete-event-modal">
                <div class="calendar-event-modal-header">
                    <h5>Delete Event</h5>
                    <a href="#" class="close"><i class="fa-solid fa-xmark"></i></a>
                </div>
                <div class="calendar-event-modal-content">
                    <p>Are you sure you want to delete this event?</p>
                </div>
                <div class="calendar-event-modal-footer">
                    <a href="#" class="delete_event">Delete</a>
                    <a href="#" class="close">Cancel</a>
                </div>
            </div>
        `);
        // Select the modal element
        let modalElement = document.querySelector('.calendar-delete-event-modal');
        // Retrieve the modal rect properties
        let rect = modalElement.getBoundingClientRect();
        // Position the modal (center center)
        modalElement.style.top = parseInt(y-(rect.height/2)) + 'px';
        modalElement.style.left = parseInt(x-(rect.width/2)) + 'px';   
        // Delete event button onclick event
        modalElement.querySelector('.delete_event').onclick = event => {
            event.preventDefault();
            // Disable the button
            modalElement.querySelector('.delete_event').disabled = true;
            // Use AJAX to delete the event
            fetch(this.ajaxUrl + "&delete_event=" + id, { cache: 'no-store' }).then(response => response.text()).then(data => {
                // Remove the modal
                modalElement.remove();
                // Fetch the calendar
                this.fetchCalendar();
                // Modal is no longer open
                this.isModalOpen = false;  
            });
        };
        this.closeEventHandler(modalElement);     
    }

    closeEventHandler(modalElement) {
        // Close button onclick event
        modalElement.querySelectorAll('.close').forEach(element => element.onclick = event => {
            event.preventDefault();
            // Remove the modal
            modalElement.remove();
            // Update the calendar CSS opacity property
            document.querySelector('.calendar').style.opacity = '1';
            // Modal is no longer open
            this.isModalOpen = false;
        });
    }

    // Function that will add the loading state
    addLoaderIcon() {
        // If the loading state has already been intialized, return and prevent further execution
        if (document.querySelector('.calendar-loader') || !document.querySelector('.calendar')) {
            return;
        }
        // Update the calendar CSS opacity property
        document.querySelector('.calendar').style.opacity = '.3';
        // Add the loader element to the HTML document
        document.body.insertAdjacentHTML('beforeend', `
            <div class="calendar-loader">
                <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
            </div>
        `);
        // Select the loader element
        let loaderElement = document.querySelector('.calendar-loader');
        // Retrieve the loader rect properties
        let rect = loaderElement.getBoundingClientRect();
        // Retrieve the calendar rect properties
        let calendarRect = document.querySelector('.calendar').getBoundingClientRect();
        // Position the loader element (center center)
        let x = (calendarRect.width / 2) + calendarRect.x;
        let y = (calendarRect.height / 2) + calendarRect.y;
        loaderElement.style.top = parseInt(y-(rect.height/2)) + 'px';
        loaderElement.style.left = parseInt(x-(rect.width/2)) + 'px';          
    }

    // Function that will remove the loading state
    removeLoaderIcon() {
        if (document.querySelector('.calendar-loader')) {
            document.querySelector('.calendar-loader').remove();
        }
    }

    // Event handlers for all calendar elements
    _eventHandlers() {
        // Retrieve the calendar rect properties
        let rect = document.querySelector('.calendar').getBoundingClientRect();
        let x = (rect.width / 2) + rect.x;
        let y = (rect.height / 2) + rect.y;
        // Calendar month previous button onclick event
        document.querySelector('.calendar .header .prev').onclick = event => {
            event.preventDefault();
            // Update the current date
            this.currentDate = document.querySelector('.calendar .header .prev').dataset.date;
            // Fetch calendar
            this.fetchCalendar();
        };
        // Calendar month next button onclick event
        document.querySelector('.calendar .header .next').onclick = event => {
            event.preventDefault();
            // Update the current date
            this.currentDate = document.querySelector('.calendar .header .next').dataset.date;
            // Fetch calendar
            this.fetchCalendar();
        };
        // Calendar month next button onclick event
        document.querySelector('.calendar .header .today').onclick = event => {
            event.preventDefault();
            // Update the current date
            this.currentDate = new Date().toISOString().substring(0, 10);
            // Fetch calendar
            this.fetchCalendar();
        };
        // Refresh the calendar
        document.querySelector('.calendar .header .refresh').onclick = event => {
            event.preventDefault();
            // Fetch calendar
            this.fetchCalendar();
        };
        // Calendar month current button onclick event
        document.querySelector('.calendar .header .current').onclick = event => {
            event.preventDefault();
            // Open the date select modal
            this.openDateSelectModal(x, y, this.currentDate);
        };
        // Iterate all the day elements, exluding the ignored elements
        document.querySelectorAll('.calendar .day_num:not(.ignore)').forEach(element => {
            // Add onclick event
            element.onclick = () => {
                // If there is already a modal open, return and prevent further execution
                if (this.isModalOpen) {
                    return;
                }
                // Add the loading state
                this.addLoaderIcon();
                // Retrieve all events for the selected day
                fetch(this.ajaxUrl + '&events_list=' + element.dataset.date, { cache: 'no-store' }).then(response => response.text()).then(data => {
                    // Remove the loading state element
                    this.removeLoaderIcon();
                    // Open the events list modal
                    this.openEventModal(x, y, element.dataset.date, element.dataset.date, data, element.dataset.label);
                });
            };
        });
    }

}