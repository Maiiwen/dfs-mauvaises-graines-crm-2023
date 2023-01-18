    var tableRows = document.querySelectorAll(".line-hover");
    tableRows.forEach(function(row) {
        row.addEventListener("click", function() {
            var url = this.getAttribute("data-url"); // Récupération de l'URL stockée dans l'attribut data-url
            window.location.href = url; // Redirection vers l'URL
        });
    });