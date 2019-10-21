document.addEventListener("DOMContentLoaded", e => {

    // Form submit
    document.querySelectorAll('.report').forEach(report => {
        let form = report.querySelector('form');

        // Common fetch
        let fetchData = async function(action) {
            return fetch(action, {
                method: "POST",
                credentials: 'same-origin',
                headers: {'X-Requested-With': 'XMLHttpRequest'},
                body: new FormData(form)
            });
        }

        // Togglers
        document.querySelectorAll('select[toggler]').forEach(select => {
            let name = select.getAttribute('toggler');
            select.addEventListener('change', e => {
                // Disable children
                document.querySelectorAll(`select[parent-toggler=${name}]`).forEach(child => child.setAttribute('disabled', 'disabled'));

                // Enable select 
                if(select.value) {
                    let input = document.querySelector(`select[parent-toggler=${name}][name=${select.value}]`);
                    if(input) {
                        input.removeAttribute('disabled');
                    }
                }
            });
        });

        // Export CSV
        form.querySelectorAll('button[value="export"]').forEach(btn => btn.onclick = e => {
            e.preventDefault();

            let format = e.target.getAttribute('format');

            fetchData(`${form.action}-${format}`)
                .then(response => response.blob())
                .then(blob => {
                    let a = document.createElement('a');
                    a.href = window.URL.createObjectURL(blob);
                    a.download = `${form.getAttribute('filename')}.${format}`;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                });
        });

        // Preview btn
        form.querySelector('button[value="preview"]').onclick = e => {
            e.preventDefault();

            fetchData(form.action.replace('export', 'preview'))
            .then(response => response.json())
            .then(data => {
                let report = form.closest('.report');
                let table = report.querySelector('table');

                report.classList.add('active');
                table.innerHTML = '';

                if(data && data.length) {
                    // Table content
                    data.forEach((row, trIndex) => {
                        if(trIndex) {
                            let tr = table.insertRow(trIndex - 1);
                            row.forEach((column, columnIndex) => tr.insertCell(columnIndex).innerHTML = column);
                            tr.insertCell(0).innerHTML = trIndex;
                        }
                    });

                    // Table Header
                    let header = table.createTHead().insertRow(0);
                    data[0].forEach((column, i) => header.insertCell(i).innerHTML = column);
                    header.insertCell(0).innerHTML = "#";
                }
            });
        }

        // Close btn
        report.querySelector('.close').onclick = e => report.classList.remove('active');
    });
});