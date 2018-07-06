const login = {
    state: {
        isLoggedIn: !!localStorage.getItem('default_auth_token'),
        userData:{},
        accessData: {
            client_id: '1_1fwfg4mreq680s0404s8g8ggkgkkgoc08skow044o08cwckc4o',
            client_secret: '3kz917qhoo6ccw8ogg8og0k8k4kw80skokg8scsco88k8wk4wk',
            grant_type: 'password'
        },
        loginData:{}
    },
    getters: {
        isLoggedIn: state => {
            return state.isLoggedIn
        },
        getUserData: state =>{
            return state.userData
        },
        getLoginData: state=>{
            return state.loginData;
        },
        getAccessData: state =>{
            return state.accessData
        },
    },
    mutations: {
        setUserData(state, data){
            state.userData = data;
        },
        setLoginData(state, data){
            state.loginData = data
        }
    },
    actions: {
       
       
    }
}

export default login;