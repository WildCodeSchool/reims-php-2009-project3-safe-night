document.getElementById('searchField').addEventListener('input', function(event) {
    const query = event.target.value;
    fetch('https://localhost:8000/programs/autocomplete?q=' + query)
        .then(response => response.json())
        .then(json => {
            let resultHtml = "";
            json.forEach(element => {
                resultHtml = resultHtml + `
                <li><a href = "/programs/${element.id}">${element.title}</a></li>
            `;
            });

        document.querySelector('#autocomplete').innerHTML = resultHtml;
        })
        .catch(() => alert('error'));

});
