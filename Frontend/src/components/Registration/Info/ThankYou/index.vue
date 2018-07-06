<template>
    <div class="container-fluid d-flex flex-column p-0">
        <app-header :itemsMenu="this.menu" :search="false" :accoutItems="false"></app-header>
        <div class="thank-block-wrap">
            <div class="thank_you pad_top_info" v-if="status">
                <div class="ty_top">
                    <span>Thank You for Registration</span>
                </div>
                <div class="round_130 step1_2_bor_col">
                    <div class="round_95">
                        <svg width="30" height="30" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg" fill="#0ecdee">
                            <path d="M1671 566q0 40-28 68l-724 724-136 136q-28 28-68 28t-68-28l-136-136-362-362q-28-28-28-68t28-68l136-136q28-28 68-28t68 28l294 295 656-657q28-28 68-28t68 28l136 136q28 28 28 68z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="block-info" :class="[{error: !this.status}, {'justify-content-center' : !this.status}]">
                <div class="ty_bot">
                    <span>{{this.reqInfo}}</span>
                </div>
            </div>
        </div>
        <app-footer></app-footer>
    </div>
</template>
<script>
import { mapGetters, mapActions } from 'vuex';
export default {
    data: function() {
        return {
            status: false,
            reqInfo: "",
            menu: [
                { name: "Welcome", link: "/welcome" },
                { name: "FAQ", link: "/faq" },
            ],
            accMenu: [
                {
                    name: "Settings",
                    link: "/settings",
                    icon: '<svg  width="15" fill="#97a7af" height="15" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1152 896q0-106-75-181t-181-75-181 75-75 181 75 181 181 75 181-75 75-181zm512-109v222q0 12-8 23t-20 13l-185 28q-19 54-39 91 35 50 107 138 10 12 10 25t-9 23q-27 37-99 108t-94 71q-12 0-26-9l-138-108q-44 23-91 38-16 136-29 186-7 28-36 28h-222q-14 0-24.5-8.5t-11.5-21.5l-28-184q-49-16-90-37l-141 107q-10 9-25 9-14 0-25-11-126-114-165-168-7-10-7-23 0-12 8-23 15-21 51-66.5t54-70.5q-27-50-41-99l-183-27q-13-2-21-12.5t-8-23.5v-222q0-12 8-23t19-13l186-28q14-46 39-92-40-57-107-138-10-12-10-24 0-10 9-23 26-36 98.5-107.5t94.5-71.5q13 0 26 10l138 107q44-23 91-38 16-136 29-186 7-28 36-28h222q14 0 24.5 8.5t11.5 21.5l28 184q49 16 90 37l142-107q9-9 24-9 13 0 25 10 129 119 165 170 7 8 7 22 0 12-8 23-15 21-51 66.5t-54 70.5q26 50 41 98l183 28q13 2 21 12.5t8 23.5z"/></svg>',
                    submenuList: [{ name: "Profile", link: "/profile" }, { name: "Company Information", link: "#" }, { name: "Company Settings", link: "#" }, { name: "Billing", link: "#" }]
                },
                {
                    name: "Email Subscription",
                    link: "/subscribe",
                    icon: '<svg fill="#97a7af" width="15" height="15" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1792 710v794q0 66-47 113t-113 47h-1472q-66 0-113-47t-47-113v-794q44 49 101 87 362 246 497 345 57 42 92.5 65.5t94.5 48 110 24.5h2q51 0 110-24.5t94.5-48 92.5-65.5q170-123 498-345 57-39 100-87zm0-294q0 79-49 151t-122 123q-376 261-468 325-10 7-42.5 30.5t-54 38-52 32.5-57.5 27-50 9h-2q-23 0-50-9t-57.5-27-52-32.5-54-38-42.5-30.5q-91-64-262-182.5t-205-142.5q-62-42-117-115.5t-55-136.5q0-78 41.5-130t118.5-52h1472q65 0 112.5 47t47.5 113z"/></svg>',
                    submenuList: []
                },
                {
                    name: "Log out",
                    link: "#",
                    icon: '<svg fill="#97a7af" width="15" height="15" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M704 1440q0 4 1 20t.5 26.5-3 23.5-10 19.5-20.5 6.5h-320q-119 0-203.5-84.5t-84.5-203.5v-704q0-119 84.5-203.5t203.5-84.5h320q13 0 22.5 9.5t9.5 22.5q0 4 1 20t.5 26.5-3 23.5-10 19.5-20.5 6.5h-320q-66 0-113 47t-47 113v704q0 66 47 113t113 47h312l11.5 1 11.5 3 8 5.5 7 9 2 13.5zm928-544q0 26-19 45l-544 544q-19 19-45 19t-45-19-19-45v-288h-448q-26 0-45-19t-19-45v-384q0-26 19-45t45-19h448v-288q0-26 19-45t45-19 45 19l544 544q19 19 19 45z"/></svg>',
                    submenuList: []
                },
            ],
            error: ""
        }
    },
    mounted() {
        if(this.$route.params.registrEmployee){
            console.log('Employee');
            this.status = true;
            this.reqInfo = "Your account is confirmed!";
        }else{
            this.geolocate();
            this.fetchConfirm(); 
        }
    },
    methods: {
        ...mapActions(['geolocate']),
        fetchConfirm() {
            let confirm_link = this.$route.query.confirm_link;
            let router = this.$router;
            if (confirm_link) {
                this.$http.get(confirm_link).then(response => {
                    this.status = true;
                    this.reqInfo = "Your account is confirmed!";
                    this.$auth.token('default_auth_token', response.body.access_token +';' + response.body.refresh_token);
                    this.$auth.watch.authenticated = true;
                    this.$auth.watch.loaded = true;
                    document.cookie='rememberMe=false';  
                    setTimeout(function() {
                       router.push({name: 'Registration', params: {step: 'step-2'}})
                    }, 1000);
                }, response => {
                    if (response.status === 404) {
                        this.reqInfo = "Confirmation link is not valid";
                        this.status = false;
                    }
                });
            }

        }
    },
    components: {
        appHeader: require('../../../Header'),
        appFooter: require('../../../Footer')
    }

}
</script>
<style lang="scss" src="../style.scss" scoped></style>
<style lang="scss" src="../../style.scss" scoped></style>



