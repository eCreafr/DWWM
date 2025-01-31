fetch('https://geo.api.gouv.fr/departements')
    .then((response)=>{
        if(!response.ok){
            throw new Error('Problème')
        }
        return response.json()
    })

    .then((myData)=>{
       
        const departementElt = document.getElementById('departement')
        for(option of myData){
            let optionElt = document.createElement('option')
            optionElt.textContent = option.nom
            optionElt.setAttribute('value', option.code )
            departementElt.appendChild(optionElt)
        }

        departementElt.addEventListener('change', (e)=>{
            let numeroDepartement = e.target.value
            fetch('https://geo.api.gouv.fr/departements/' + numeroDepartement + '/communes')
            .then((response)=>{
                if(!response.ok){
                    throw new Error('Problème')
                }
                return response.json()
            })
            .then((myData)=>{
                
                myData.sort()
                const communeElt = document.getElementById('commune')
                communeElt.innerHTML =''
                let optionDefault = document.createElement('option')
                optionDefault.textContent = 'Choisissez votre commune'
                optionDefault.setAttribute('selected', 'selected')
                communeElt.appendChild(optionDefault)
                for(option of myData){
                    let optionElt = document.createElement('option')
                    optionElt.textContent = option.nom
                    optionElt.setAttribute('value', option.nom )
                    communeElt.appendChild(optionElt)
                }

                communeElt.addEventListener('change', (e)=>{
                    
                    Meteo(e.target.value)
                })
            })
            
        })

    })