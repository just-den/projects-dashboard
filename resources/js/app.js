import _upperFirst from 'lodash/upperFirst' 
import _camelCase from 'lodash/camelCase'  
import Vue from 'vue'
import router from './routes';
import CKEditor from 'ckeditor4-vue';
import { Helper } from './utilities/helper'
import { Ajax } from './utilities/ajax'

import 'nprogress/nprogress.css'		
import NProgress from 'nprogress'

window.ajax = new Ajax()
window.NProgress = NProgress
NProgress.configure({ showSpinner: false, easing: 'ease', speed: 500 })


Vue.use(CKEditor);

const requireComponent = require.context(
    './components/global',
    false,
    /[A-Z]\w+\.(vue|js)$/
)

requireComponent.keys().forEach(fileName => {
    const componentConfig = requireComponent(fileName)

    const componentName = _upperFirst(
        _camelCase(fileName.replace(/^\.\/(.*)\.\w+$/, '$1'))
    )
    
    Vue.component(componentName, componentConfig.default || componentConfig)
})


let app = new Vue({
    el: '#app',
    router,
    data:{
        test: 10,
        H: new Helper(),        
        baseCurrency: 'usd',
        baseCurrencyRate: 0,
    	currentDate: {
    		day: '',
    		month: '',
    		year: '',
    	},
        editorData: '',
        editorConfig: {},
        currencies: [],
        password: {
            settings:{
                length: 12,
                plain: false
            },
            new: '',
            copied: false
        },
        CSRF: '',
        mobileInfoVar: false,
        mobileNavVar: false,
        mainBlockStyles: {
            width: '58%',
            left: '50%'
        },
        weather: []
    },
    methods:{
        newPassword(){
            let length = this.password.settings.length
            let plain = this.password.settings.plain
            this.password.new = this.H.generatePassword(length,plain)
        },
        copyPassword(){
            this.H.copyToClipboard()
            this.password.copied = true
            setTimeout(()=>{
                this.password.copied = false
            },800)
        },
        async getData(){
            NProgress.start()
            const c = await this.getCurrencies()
            const w = await this.getWeather()
            return {c, w}
        },
        getCurrencies(){         
           return ajax               
            .get('/api/currencies')              
            .then(data =>  {
                    try{
                        const parsed = JSON.parse(data)
                        this.currencies = parsed.slice(0,-2)

                        this.currencies[0].sign = '$'
                        this.currencies[0].buy =  Number( parsed[0].buy ).toFixed(2)
                        this.currencies[0].sale =  Number( parsed[0].sale ).toFixed(2)

                        this.currencies[1].sign = '€'
                        this.currencies[1].buy =  Number( parsed[1].buy ).toFixed(2)
                        this.currencies[1].sale =  Number( parsed[1].sale ).toFixed(2)

                        this.currencies.forEach(item => {
                            if(item.ccy.toLowerCase() === this.baseCurrency){
                                this.baseCurrencyRate = item.sale
                            }
                        });
                        return { status: true } 
                    }catch(e){
                        console.log('PARSING API CURRENCIES ERROR: ',e.message)
                        return { status: false, message: 'API CURRENCIES ERROR' }
                    }
                    
                },             
                err => {
                    console.log('err: ',err)  
                    return { status: false, message: 'API CURRENCIES ERROR' }
                }           
              ) 
        },
        getWeather(){          
            return ajax               
            .get('/parser/weather')              
            .then(data =>  {
                    try{
                        const parsed = JSON.parse(data)
                        this.weather = parsed
                        // console.log('parsed: ',parsed)
                        return { status: true } 
                    }catch(e){
                        this.weather = []
                        console.log('PARSING API WEATHER ERROR: ',e.message)
                        return { status: false, message: 'API WEATHER ERROR' }
                    }
                    
                },             
                err => {
                    console.log('err: ',err)  
                    return { status: false, message: 'API WEATHER ERROR' }
                }           
              )                    

        },
        mobileInfo(){
            this.mobileInfoVar = !this.mobileInfoVar
            this.mobileToggle()
        },
        mobileNav(){
            this.mobileNavVar = !this.mobileNavVar
            this.mobileToggle()           
        },
        mobileToggle(){
             if(!this.mobileNavVar && this.mobileInfoVar){
                this.mainBlockStyles = {
                     width: '70%',
                     left: '58%'                   
                }               
            }else if(this.mobileNavVar && !this.mobileInfoVar){
                this.mainBlockStyles = {
                     width: '70%',
                     left: '42%'                   
                }                   
            }else if(!this.mobileNavVar && !this.mobileInfoVar){
                 this.mainBlockStyles = {
                     width: '58%',
                     left: '50%' 
                }                  
            }else if(this.mobileNavVar && this.mobileInfoVar){
                 this.mainBlockStyles = {
                     width: '90%',
                     left: '50%'                   
                }                 
            }
            this.H.localStorageAddData(
                    {
                        mainBlockStyles: this.mainBlockStyles,
                        mobileNavVar: this.mobileNavVar,
                        mobileInfoVar: this.mobileInfoVar
                    }
                )           
        }
    },
    mounted(){

        // DATE
        this.currentDate = this.H.date()
    	setInterval(()=>{
    		this.currentDate = this.H.date()
    	},60000)

        // PASSWORD
        this.password.new = this.H.generatePassword()

        // CSRF
        this.CSRF = document.querySelector('meta[name="csrf-token"]').content


        const mainBlockStylesParsed = JSON.parse(localStorage.getItem("vue_dashboard_mobile_navs"))

        if(mainBlockStylesParsed){
            if(mainBlockStylesParsed.mainBlockStyles){
                this.mainBlockStyles = mainBlockStylesParsed.mainBlockStyles
            }
             this.mobileInfoVar = mainBlockStylesParsed.mobileInfoVar
             this.mobileNavVar = mainBlockStylesParsed.mobileNavVar 
         }
        
         this.getData().then(data => {
                if(data.c.status && data.w.status){
                    NProgress.done()
                }else{
                    alert('ERROR. SEE CONSOLE')
                    console.log('FINAL DATA: ',data)
                }
                
            })	
            
        // API CURRENCIES
        // reload each 45 min
        setInterval(()=>{
            this.getCurrencies()
        },45 * 60 * 1000)

        // PARSER WEATHER
        // reload each 20 min
        setInterval(()=>{
            this.getWeather()
        },20 * 60 * 1000)


        
        }

});
