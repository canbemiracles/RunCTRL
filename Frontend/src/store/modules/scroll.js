export default {

  state: {
    validate: false,
    validateArr: []
  },

  getters: {
    validate: ({ validate }) => validate,
    validateArr: ({ validateArr }) => validateArr
  },

  mutations: {
    setValidateArr(state, { index, val }) {
      state.validateArr[index] = val;
    },
    initValidateArr(state, data) {
      state.validateArr = data;
    },
    setValidate(state, data) {
      state.validate = data;
    }
  }
}
