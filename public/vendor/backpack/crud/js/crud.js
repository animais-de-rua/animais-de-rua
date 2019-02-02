/*
*
* Backpack Crud
*
*/

approveAppointment = (elem, id, option) => {
    if(!confirm(window.Laravel.translations.confirmApprove))
        return false;

    fetch('/admin/api/appointment/approve', {
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        method: 'POST',
        body: JSON.stringify({
            'id': id,
            'option': option,
        }),
    }).
    then(response => response.json().then(data => {
        if(data.result) {
            let btn = elem.closest('div').querySelector('.btn');
            let status = elem.closest('tr').querySelector('.status');

            status.innerHTML = window.Laravel.translations['approved_option_' + option];
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-default');
            btn.setAttribute('disabled', true);
        }
    }));

    return false;
}

approveTreatment = (btn, id, option) => {
    if(!confirm(window.Laravel.translations.confirmApprove))
        return false;

    fetch('/admin/api/treatment/approve', {
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        method: 'POST',
        body: JSON.stringify({
            'id': id
        }),
    }).
    then(response => response.json().then(data => {
        if(data.result) {
            let status = btn.closest('tr').querySelector('.status');

            status.innerHTML = window.Laravel.translations['approved'];
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-default');
            btn.setAttribute('disabled', true);
        }
    }));

    return false;
}

document.addEventListener("DOMContentLoaded", function() {

    // Duplicate actions row on show
    let table = document.querySelector('#show-table > tbody');
    if(table) {
        let trs = table.querySelectorAll('tr');
        let actions = trs[trs.length - 1];

        table.insertBefore(actions.cloneNode(true), table.querySelector('tr'));
    }

    // Paint approved rows on process treatments table
    document.querySelectorAll('td.approved').forEach(e => e.closest('tr').classList.add('approved'));

});