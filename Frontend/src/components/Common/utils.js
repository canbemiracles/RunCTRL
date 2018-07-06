import {store} from '../../store';
import router from '../../router';
export const abbrName = function (value){
    if(value){
        let nameStr = value.split(' ');
        let abbr;
        if(nameStr.length==1){
            abbr = nameStr[0][0];
        }
        if(nameStr.length>=2){
            abbr = nameStr[0][0] + nameStr[1][0]
        }
        return abbr;
    }
}
export const getCurrencyValue = function (value, minFractionDigits, maxFractionDigits){
    let currency, formatter;
    if($.isEmptyObject(store.getters.company)){
        store.dispatch('fetchCompanyData').then(()=>{
            if(store.getters.company.currency){
                currency = store.getters.company.currency.currency;
                formatter = new Intl.NumberFormat(store.getters.language, {
                    style: "currency",
                    currency: currency.toUpperCase(),
                });
                
                return  formatter.format(value); 
            }else{
                return value;
            }
        })
    }else{  
        if(store.getters.company.currency){
            currency = store.getters.company.currency.currency;
            formatter = new Intl.NumberFormat(store.getters.language, {
                style: "currency",
                currency,
                minimumFractionDigits: minFractionDigits,
                maximumFractionDigits: maxFractionDigits
            });
            return  formatter.format(value);
        }else{
            return value;
        }        
    } 
}
export const loadJs = function (url, callback) {
    jQuery.ajax({
      url: url,
      dataType: 'script',
      success: callback,
      async: true
    });
}
export const validateEmail = function (email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}
export const guardRoute = function(step, to, from, next){
    console.log(router.app.$auth.check());
    console.log(step);
    if(router.app.$auth.check() && (from.name == null || from.name == 'login') && step != null){
         //принудительный редирект на шаг регистрации если рег-я не пройдена при первом входе
         router.app.$store.dispatch('getRouteFromStep').then(pathRoute=>{
            if(to.name != pathRoute.name){
                console.log('pass 1');
                next(pathRoute);
            }else{
                console.log('pass 2');
                next();  
            }
        });
    }else if(router.app.$auth.check() && from.name!=null && from.path != to.path){
        if(step != null){
            if(step == 1){
                console.log('pass 3');
                    next(false);
            }else if(step == 2){
                if(to.name != 'Registration' && to.params && to.params.step != 'step-2'){
                    console.log('pass 4');    
                    next(false);
                }else{
                    console.log('pass 5');    
                    next();
                }
            }else if(step == 3){
                if(to.name != 'Registration' && to.params && (to.params.step != 'step-2' || to.params.step != 'step-3')){
                    console.log('pass 6');    
                    next(false);
                }else{
                    console.log('pass 7');    
                    next();
                }
           }else{
               console.log('pass 8')
               next();
           }
        }else{
            //при пройденной регистрации запрет перехода на указанные страницы
            if( to.name!='Welcome' && to.path!='/registration/step-2' 
                && to.path!='/registration/step-3' 
                && to.name!='ThankYou'
                && to.name!='signUp'
                && to.name!='fogot-password'
                && to.name!='reset_password'
                && to.name!="login")
            {
                console.log('pass 9');
                next();    
            }else{
                console.log('pass 10');
                next({name: from.name, replace: true});
            }
        }
    }else if(to.name=='ThankYou' && from.name!=null && from.name!=to.name && from.name !='Welcome' && from.name !='RegistEmployee'){
        console.log('pass 11');
        next({name: from.name, replace: true});
    }else if(to.name=='Welcome' && from.name!='login' && from.name!='signUp' && from.name!=to.name && from.name!='ThankYou') {
        console.log('pass 12');
        next({name: from.name, replace: true});
    }else if(from.name=='Welcome' && (to.name=='login' || to.name == 'ThankYou') && from.name!=to.name) {
        console.log('pass 13');
        next();
    }else if(from.name=='Welcome' && to.name!='login' && from.name!=to.name) {
        console.log('pass 14');
        next({name: 'login'}) 
    }else{
        console.log('pass 15');
        next(); 
    }
}






