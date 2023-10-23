let select_all = document.querySelector('.select-all');
let checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
if (select_all) {
	select_all.addEventListener('change', event => {
		for (i = 0; i < checkboxes.length; i++) {
			checkboxes[i].checked = select_all.checked;
			if (select_all.checked) {
				checkboxes[i].closest('tr').classList.add('selected');
			} else {
				checkboxes[i].closest('tr').classList.remove('selected');
			}
		}
	});
	for (i = 0; i < checkboxes.length; i++) {
		let checkbox = checkboxes[i];
		checkbox.addEventListener('change', event => {
			if (checkbox.checked === false) {
				select_all.checked = false;
				checkbox.closest('tr').classList.remove('selected');
			} else {
				checkbox.closest('tr').classList.add('selected');
			}
			if (document.querySelectorAll('tbody input[type="checkbox"]:checked').length == checkboxes.length) {
				select_all.checked = true;
			}
		});
	}
}
if (document.querySelector('.toggle-filters-btn')) {
	document.querySelector('.toggle-filters-btn').addEventListener('click', event => {
		event.preventDefault();
		document.querySelector('.filters .dropdown').classList.toggle('active');
	});
	window.addEventListener('click', event => {
		if (!document.querySelector('.filters').contains(event.target)) {
			document.querySelector('.filters .dropdown').classList.remove('active');
		}
	});
}
if (document.querySelector('.bulk-action')) {
    document.querySelector('.bulk-action').addEventListener('change', event => {
        if (event.target.value === 'delete') {
            if (confirm('Are you sure you want to delete the selected records?')) {
                document.querySelector('.crud-form').submit();
            }
        }
        if (event.target.value === 'export' || event.target.value === 'edit') {
            document.querySelector('.crud-form').submit();
        }
    });
}