import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter);

const Login = () => import('./components/Login');
const SignIn = () => import('./components/Login/SignIn');
const SignUp = () => import('./components/Login/SignUp');
const ForgotPass = () => import( './components/Login/ForgotPass');
const ResetPass = () => import('./components/Login/ResetPass');

export const routes = [
  {
    path: "",
    component: Login,
    children: [
      { path: '', name: 'login', component: SignIn, props: true },
      { path: '/sign-up', name: "signUp", component: SignUp, props: true },
      { path: '/fogot-password', name: "forgotPass", component: ForgotPass },
      { path: '/reset_password', name: "resetPass", component: ResetPass },
    ],
    meta: { auth: false }
  },
  {
    name: 'branchPage',
    path: "/branch-page/:id",
    component: () => import('./components/BranchPage'),
    meta: { auth: true },
    props: true
  },
  {
    name: 'QuickTaskFlow',
    path: "/task",
    component:  () => import('./components/QuickTaskFlow'),
    meta: { auth: true }
  },
  {
    name: 'Registration',
    path: "/registration/:step",
    component:  () => import('./components/Registration'),
    meta: { auth: true },
    props: true
  },
  {
    name: 'RegistEmployee',
    path: "/registration-employee",
    component:  () => import('./components/Registration/EmployeeForm'),
    meta: { auth: false },
  },
  {
    name: 'Registration4Card',
    path: "/step4-card",
    component:  () => import('./components/Registration/Step4Card'),
    meta: { auth: true }
  },
  {
    name: 'Registration4',
    path: "/step4",
    component:  () => import('./components/Registration/Step4Payment'),
    meta: { auth: true }
  },
  {
    name: 'Welcome',
    path: "/welcome",
    component:  () => import('./components/Registration/Info/Welcome'),
    meta: { auth: false}
  },
  {
    name: 'ThankYou', path: "/confirmation",
    component:  () => import('./components/Registration/Info/ThankYou'),
    meta: { auth: false }
  },
  {
    path: "/branch-flow",
    component:  () => import('./components/BranchCreation'),
    children:[
      {
        path: '',
        name: 'branchFlow',
        props: false,
        component:  () => import('./components/BranchCreation/BranchFlow') },
      {
        path: '/branch-flow/:id',
        name: 'branchFlowEdit',
        props: true,
        component:  () => import('./components/BranchCreation/BranchFlow')
      },
      { path: '/create-assigments', props: true, name: 'createTaskFlow', component:  () => import('./components/BranchCreation/CreateTaskFlow') },
    ],
    meta: { auth: true }
  },
  {
    path: "/employees-list",
    name: 'employeesList',
    component:  () => import('./components/EmployeesList'),
    meta: { auth: true }
  },
  {
    path: "/employee-profile-page/:id",
    name: 'employeeProfile',
    component:  () => import('./components/EmployeeProfilePage'),
    meta: { auth: true },
    props: true
  },
	{
    path: "/dashboard-page",
    name: "dashboard",
		component:  () => import('./components/DashboardPage'),
		meta: { auth: true }
	},
	{
		path: "/reports-page",
		component:  () => import('./components/ReportsPage'),
		meta: { auth: true }
	},
	{
    path: "/branch-data",
    name: "branchList",
		component:  () => import('./components/BranchData'),
		meta: { auth: true }
  },
  {
    path: "/task-calendar",
    name: "taskCalendar",
		component:  () => import('./components/CalendarPage'),
		meta: { auth: true }
  },

  {
    path: "/settings",
    component: () => import('./components/SettingsPage'),
    children:[
      { path: '/notification', name: 'notification', component:  () => import('./components/SettingsPage/Notification') },
      { path: '/profile', name: 'profile', component:  () => import('./components/SettingsPage/Profile') },
      { path: '/company_information', name: 'company_information', component:  () => import('./components/SettingsPage/CompanyInfo') },
      { path: '/company_settings', name: 'company_settings', component:  () => import('./components/SettingsPage/CompanySettings') },
      { path: '/billing', name: 'billing', component:  () => import('./components/SettingsPage/Billing') },
      { path: '/security', name: 'security', component:  () => import('./components/SettingsPage/Security') },
      { path: '/email_subscription', name: 'email_subscription', component:  () => import('./components/SettingsPage/EmailSubscription') },
    ],
    meta: { auth: true }
  },

  {
    name: 'page404',
    path: "*",
    component:  () => import('./components/Page404'),
    meta: { auth: false }
  }


];
export default new VueRouter({
  base: __dirname,
  routes
});
