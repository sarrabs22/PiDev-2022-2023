const rating = document.getElementsByName('rating');

for (let i = 0; i < rating.length; i++) {
    rating[i].addEventListener('change', function () {
        console.log(this.value);
        // vous pouvez stocker la valeur de la note dans une variable, l'envoyer à un serveur, etc.
    });
}
