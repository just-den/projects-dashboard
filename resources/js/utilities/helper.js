import { months } from '@/js/storage/months'

export class Helper{ 

    	date(){
      		const date = new Date()
    		const monthObj = months.find( month => date.getMonth() === month.id )
    		return { 
    				day: date.getDate(),
    				month: monthObj.name.en, 
    				year: date.getFullYear() 
    		 }
    	}

    	/*

				https://owasp.org/www-community/password-special-characters
				https://stackoverflow.com/questions/1497481/javascript-password-generator

    	*/

        generatePassword(length=12,plain=false){
        	length = length === 12 ? 12 : 6
            const charset = plain  
                        ? 
                     'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'                    
                        : 
                     'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!"#$%&\'()*+,-./:;<=>?@[\]^_`{|}~' 
            let retVal = "";
            for (var i = 0, n = charset.length; i < length; ++i) {
                retVal += charset.charAt(Math.floor(Math.random() * n));
            }
            return retVal;
        }

        copyToClipboard(){                                                 
                                                                    
                document.querySelector('#password_new').select();
                document.execCommand("copy");                                                                                                                         
                window.getSelection().removeAllRanges()                                                                                                                        

        }


        localStorageAddData(data){
            data = JSON.stringify(data)
            try {           
              localStorage.setItem('vue_dashboard_mobile_navs', data)
            } catch (e) {
              if (e == QUOTA_EXCEEDED_ERR) {
               console.log('LocalStorage limit exceeded')
              }
            }
        }

        gridMasonry(){
            
            const resizeMasonryItem = (item) =>{
              const grid = document.querySelector('.masonry')
              const rowGap = parseInt(window.getComputedStyle(grid).getPropertyValue('grid-row-gap')),
                    rowHeight = parseInt(window.getComputedStyle(grid).getPropertyValue('grid-auto-rows'))

              const rowSpan = Math.ceil((item.querySelector('.masonry-content').getBoundingClientRect().height+rowGap)/(rowHeight+rowGap))

              item.style.gridRowEnd = 'span '+ rowSpan
            }


            const fitItemSize = () => {
              const allItems = document.querySelectorAll('.masonry-item')
              Array.from(allItems).forEach(item => resizeMasonryItem(item))
            }

            ['DOMContentLoaded','load', 'resize'].forEach( event => window.addEventListener(event, fitItemSize))       

             const stopInterval = setInterval(()=>{      
                const markerEl = document.querySelector('.masonry-content')   
                if(markerEl){   
                    clearInterval(stopInterval)
                    document.querySelector('.masonry').addEventListener('transitionend',()=>{
                        fitItemSize()   
                    },false)   
                }   
            },160)                       

        }

        setPointsToNumber(n){
            n = n.toString()
            if(n.length > 3){
                let s = ''
                let j = 0
                for(let i = n.length - 1; i >= 0; i--){	
                        if(j % 3 === 0 && j !== 0){
                            s += '.' + n[i]
                        }else{
                            s += n[i]
                        }
                        j++
                    }
                    n = ''			
                for(let i = s.length - 1; i >= 0; i--){
                    n += s[i]
                    }		
            }
            return n
        }


        roundTo(v,r){
            return Math.round(v / r) * r
        }
}
