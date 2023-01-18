import axios from "axios";

let address = document.querySelector('#company_street')
let liste = document.querySelector('#liste')
let zipcode = document.querySelector('#company_zipCode')
let city = document.querySelector('#company_city')

const curl = 'https://api-adresse.data.gouv.fr/search/?q='
const endCurl = '&type=housenumber&autocomplete=1&limit=5'

let value;
let proposition = [];

address.addEventListener('keyup', (e)=>{
    liste.innerHTML = '';
    if(e){
    value = address.value
    if( value.length > 5){             
      axios(curl + value + endCurl)             
      .then(response=>{
        proposition = response.data.features;
        console.log(proposition.slice(0,4));
      });           
    }
    proposition.slice(0,4).forEach(element => {
      let li = document.createElement('li')
      li.textContent = element.properties.label;
      console.log(element.properties);
      li.classList.add('item')
      li.dataset.name= element.properties.name
      li.dataset.postcode= element.properties.postcode
      li.dataset.city= element.properties.city
      liste.classList.add('style')
      li.classList.add('style')
      liste.append(li);
    });
    if (liste.length == 0)
    {
      liste.innerHTML = '';
      liste.classList.remove('style')
      li.classList.remove('style')
    }
    let items = document.querySelectorAll('.item')
    items.forEach(item => {
        item.addEventListener('click', (e)=> {
            address.value = item.dataset.name
            zipcode.value = item.dataset.postcode
            city.value = item.dataset.city
            liste.innerHTML = '';
            liste.classList.remove('style')
        })
    })
  } 
})

// Recherche


// fetch(curl + cdc + endCurl)
// .then(response => console.log(response.json()))