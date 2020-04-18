export class Ajax{

	get(url,data=''){
	
		return new Promise((resolve, reject) => {					
		    const request = new XMLHttpRequest()					
							
		    request.addEventListener('readystatechange', (e) => {					
		        if (e.target.readyState === 4 && e.target.status === 200) {						            					
		            resolve(e.target.responseText)					
		        } else if (e.target.readyState === 4) {
                    const _reject = 'PROMISE REJECT: ' + e.target.responseText + ' ' + e				
		            reject(_reject)			
		        }					
		    })

            if(data){
                let dts = '?'
                for(let i in data){
                    if(data.hasOwnProperty(i)){
                        dts += `${i}=${data[i]}&` 
                    }
                }   
                data = dts.slice(0, -1);               
            }

		    request.open('GET', `${url}${data}`)					
		    request.send()					
		})					

	}

	post(url,data,csrf,stringify=false){
           return new Promise((resolve, reject) => {

                const request = new XMLHttpRequest()

                request.addEventListener('readystatechange', (e) => {
                    if (e.target.readyState === 4 && e.target.status === 200) {
                        resolve(e.target.responseText)
                    } else if (e.target.readyState === 4) {
                        const _reject = 'PROMISE REJECT: ' + e.target.responseText + ' ' + e                
                        reject(_reject) 
                    }
                })               
              
                if(!stringify){
                    const params = new URLSearchParams()
                    for(let i in data){
                        if(data.hasOwnProperty(i)){
                            params.append(i, data[i])
                        }
                    }
                    data = params
                }else{
                    data = JSON.stringify(data)
                }


                // console.log(JSON.stringify(data))                    
                request.open('POST', url)
                request.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
                request.setRequestHeader('X-CSRF-TOKEN', csrf)
                request.send(data)		
		})

    }


    put(url,data,csrf,stringify=false){
           return new Promise((resolve, reject) => {

                const request = new XMLHttpRequest()
                const ID = data.id

                request.addEventListener('readystatechange', (e) => {
                    if (e.target.readyState === 4 && e.target.status === 200) {
                        resolve(e.target.responseText)
                    } else if (e.target.readyState === 4) {
                        const _reject = 'PROMISE REJECT: ' + e.target.responseText + ' ' + e                
                        reject(_reject) 
                    }
                })               
              
                if(!stringify){
                    const params = new URLSearchParams()
                    for(let i in data){
                        if(data.hasOwnProperty(i)){
                            params.append(i, data[i])
                        }
                    }
                    data = params
                }else{
                    data = JSON.stringify(data)
                }

                // ДЛЯ МНОЖЕСТВЕННОГО ОБНОВЛЕНИЯ
                url = ID ?  `${url}/${ID}` : url

                request.open('PUT', url)
                request.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
                request.setRequestHeader('X-CSRF-TOKEN', csrf)
                request.send(data)        
        })

    }

    delete(url,id,csrf){
           return new Promise((resolve, reject) => {

                const request = new XMLHttpRequest()

                request.addEventListener('readystatechange', (e) => {
                    if (e.target.readyState === 4 && e.target.status === 200) {
                        resolve(e.target.responseText)
                    } else if (e.target.readyState === 4) {
                        const _reject = 'PROMISE REJECT: ' + e.target.responseText + ' ' + e                
                        reject(_reject) 
                    }
                })               
                   
                request.open('DELETE', `${url}/${id}`)
                request.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
                request.setRequestHeader('X-CSRF-TOKEN', csrf)
                request.send()        
        })

    }
}