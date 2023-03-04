import jsPDF from 'jspdf';

document.getElementById('generate-pdf').addEventListener('click', () => {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '/don/data', true);
    xhr.responseType = 'json';
    xhr.onload = () => {
        if (xhr.status === 200) {
            const data = xhr.response;
            const doc = new jsPDF();
            let y = 20;
            data.forEach((don) => {
                doc.text(`Name: ${don.NameD}`, 20, y);
                doc.text(`Quantity: ${don.quantite}`, 20, y + 10);
                doc.text(`Description: ${don.Description}`, 20, y + 20);
                doc.text(`Localisation: ${don.Localisation}`, 20, y + 30);
                doc.text(`Category: ${don.category.NameCa}`, 20, y + 40);
                y += 50;
            });
            doc.save('dons.pdf');
        }
    };
    xhr.send();
});