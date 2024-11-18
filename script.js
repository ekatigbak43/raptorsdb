function handleErrors(response) {
    if (!response.ok) {
        throw Error(response.statusText);
    }
    return response;
}

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('search-form').addEventListener('submit', function (e) {
        e.preventDefault();

        let playerName = document.getElementById('player-name').value.trim();
        let season = document.getElementById('season').value.trim();

        const apiUrl = `http://b8c40s8.143.198.70.30.sslip.io/api/PlayerDataTotals/team/TOR`;

        fetch(apiUrl)
            .then(handleErrors)
            .then(response => response.json())
            .then(data => {
                const filteredData = data.filter(player => {
                    const nameMatch = playerName === '' || player.playerName.toLowerCase().includes(playerName.toLowerCase());
                    const seasonMatch = season === '' || player.season == season;
                    return nameMatch && seasonMatch;
                });

                displayResults(filteredData);
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                document.getElementById('results').innerHTML = `<p>Something went wrong: ${error.message}</p>`;
            });
    });

    function displayResults(data) {
        const searchResults = document.getElementById('results');
        searchResults.innerHTML = '';

        if (data.length > 0) {
            const table = document.createElement('table');
            table.classList.add('table', 'table-striped', 'table-bordered');

            const headers = `
                <thead class="table-dark">
                    <tr>
                        <th>Player Name</th>
                        <th>Position</th>
                        <th>Season</th>
                        <th>Age</th>
                        <th>Games</th>
                        <th>Games Started</th>
                        <th>Points</th>
                        <th>Total Rebounds</th>
                        <th>Assists</th>
                    </tr>
                </thead>
            `;

            const bodyContent = data.map(player => `
                <tr>
                    <td>${player.playerName}</td>
                    <td>${player.position}</td>
                    <td>${player.season}</td>
                    <td>${player.age}</td>
                    <td>${player.games}</td>
                    <td>${player.gamesStarted}</td>
                    <td>${player.points}</td>
                    <td>${player.totalRb}</td>
                    <td>${player.assists}</td>
                </tr>
            `).join('');

            table.innerHTML = headers + `<tbody>${bodyContent}</tbody>`;
            searchResults.appendChild(table);
        } else {
            searchResults.innerHTML = '<p>No players found. Try again.</p>';
        }
    }
});