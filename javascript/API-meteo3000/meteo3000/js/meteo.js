function Meteo(commune){

    //Sans accent, sans Majuscule
    commune = commune.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')
    

    const urlAPI = 'https://www.prevision-meteo.ch/services/json/'


fetch(urlAPI + commune)
    .then((response) => {

        if (!response.ok) {
            throw new Error('Ya un bins')
        }
        return response.json()
    })

    .then((myData) => {
        
        const jours = [myData.fcst_day_0, myData.fcst_day_1, myData.fcst_day_2]
        console.log(jours)

        const mainElt = document.getElementById('main')
        mainElt.innerHTML = ''

        //Le titre
        const h2Elt = document.createElement('h2')
        h2Elt.classList.add('subtitle')
        h2Elt.classList.add('my-5')
        h2Elt.textContent = 'Météo pour la ville de ' + myData.city_info.name
        mainElt.appendChild(h2Elt)


        const rowElt = document.createElement('div')
        rowElt.classList.add('row')
        rowElt.classList.add('my-3')
        mainElt.appendChild(rowElt)

        for(let jour of jours){
            let colElt = document.createElement('section')
            colElt.classList.add('col-12')
            colElt.classList.add('col-sm-6')
            colElt.classList.add('col-md-4')
            colElt.style.textAlign = 'center'
            rowElt.appendChild(colElt)

            let h3Elt = document.createElement('h3')
            h3Elt.textContent = jour.day_long
            colElt.appendChild(h3Elt)

            let imageElt = document.createElement('img')
            imageElt.setAttribute('src', jour.icon_big)
            colElt.appendChild(imageElt)

            let tempsElt = document.createElement('p')
            tempsElt.textContent = 'Min : '+ jour.tmin + '°C - Max : ' + jour.tmax+ '°C'
            colElt.appendChild(tempsElt)
        }
    })

    .catch((error) => {
         const mainElt = document.getElementById('main')
        mainElt.innerHTML = ''

        mainElt.textContent = 'PAs de météo pour la commune de ' + commune + '!'
    })



}





