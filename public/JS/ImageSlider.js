let spanDes;
let mytitle;
let title;


window.onload = function() {
    // let imgs = document.querySelectorAll('img[class^="slide"]');
    // let lebtn = document.querySelectorAll('div.buttons');
    spanDes = document.querySelector('span.description');
    // console.log(lebtn);
    // imgs.forEach(img => {
    //     lebtn.addEventListener('click', function() {
    //         getDes(img.alt);
    //     });
    // });

    spanDes.innerHTML = '<b>Prix : 250€ </b><br>LUCY -<br> robe en pointillés Midi Robe en coton évasé forme Pologne <br> robe faite à la main <br> robe 100% coton <br> robe vintage <br>été <br> made in Poland';
}


function getDes(nom) {
    //  let description;
    if (nom == 'robeBlue') {
        spanDes.innerHTML = "<b>Prix : 250€ </b><br>LUCY -<br> robe en pointillés Midi Robe en coton évasé forme Pologne <br> robe faite à la main <br> robe 100% coton <br> robe vintage <br>été <br> made in Poland";
    }
    if (nom == 'legging') {
        spanDes.innerHTML = "<b>Prix : 150€</b> <br>maine d'utilisation: yoga,<br> fitness Matériau principal: 80% polyamide, 20% élasthanne <br>Type de matériau: fibres synthétiques <br>Propriétés du matériau: respirant, stretch <br> Durabilité: contient du polyamide recyclé <br>Coupe: ajustée <br> Caractéristiques: taille élastique <br>Autres informations: cousu à la main en Italie";
    }
    if (nom == 't') {
        spanDes.innerHTML = "<b>Prix : 100€ </b><br>Détails produit <br>•  Cardigan col V<br> •  Gilet court<br>•  Manches longues<br> •  Col V<br> •  Fermeture boutonnée<br> Composition et Entretien •  54% polyester, 20% acrylique, 10% laine, 10% alpaga, 6% élasthanne<br>•  Pour l'entretien, merci de vous référer aux indications figurant sur l'étiquette du produit<br> Couleurs Bleu, Camel, Marron-Tigers Eye <br>Tailles XS, S, M, L, XL";
    }
    if (nom == 'robeBlanc') {
        spanDes.innerHTML = "<b>Prix : 350€</b> <br>Robe courte par Simmi<br> Tout un style<br>Coutures contrastantes<br> Encolure ronde<br>Modèle asymétrique<br> Coupe moulante <br>ENTRETIEN<br> Lavage en machine conformément aux instructions sur l'étiquette d'entretien";
    }
    if (nom == 'floral') {
        spanDes.innerHTML = "<b>Prix : 250€ </b><br>Caractéristiques:<br> Magnifique robe de style des années 50-60 avec jupe évasée Cette robe est l\'incarnation de l\'élégance et de la décadence. Cette robe est très mignonne bien coupée et épouse parfaitement vos courbes. Cette robe est très flatteuse et fidèle à la taille. La longueur est la longueur du genou. C’est la longueur parfaite pour s’user et fonctionne bien pour les filles courbes.C’est vintage une ligne de robes de mariée de fête de travail. Retro femme col rond sexy a-line robe coupe parfaite assorti avec un chapeau, talons / coins / tongs, joli collier de boucles d'oreilles, bijoux, vous rendre plus féminine.";
    }


}




function getPageArticle(nom, prix) {
    console.log(nom);
    title = nom;
    window.location.href = 'E-commerce_article.html?nomArticle=' + nom + '&prixArticle=' + prix;

}