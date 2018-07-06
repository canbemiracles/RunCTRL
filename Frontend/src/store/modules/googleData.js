const googleData = {
  state: {
    apiKey: 'AIzaSyBnNacQuSZNFnZf2vUX65KkUqxA70EsRFI',
    language: 'en-US',
    location: null,
    time_zone: null
  },
  getters: {
    apiKey: ({
      apiKey
    }) => (apiKey),
    language: ({
      language
    }) => (language),
  },
  mutations: {
    setLanguage(state, data) {
      state.language = data
    },
    setLocation(state, data) {
      state.location = data
    },
  },
  actions: {
    geolocate({
      state,
      commit,
      dispatch
    }) {
      let located = false;

      function locateByIP() {

        $.ajax({
          url: `https://freegeoip.net/json/`,
          type: 'GET',
          dataType: 'json'
        }).done(res => {
          let coords = {
            lat: res.latitude,
            lng: res.longitude
          }
          commit('setLocation', coords);
          dispatch('getAdressFromCoords', coords);
          dispatch('getTimeZone');
        });
      }
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
          let coords = {
            lat: position.coords.latitude,
            lng: position.coords.longitude
          }
          located = true;
          commit('setLocation', coords);
          dispatch('getAdressFromCoords', coords);
          dispatch('getTimeZone');
        }, function(error) {
          if (error.code == error.PERMISSION_DENIED) {
            console.log("you denied me :-(");
          }
          locateByIP()
        })
      }
      if (!located) {
        locateByIP()
      }
    },
    getLanguage({
      state,
      commit
    }) {
      commit('setLanguage', navigator.language || navigator.browserLanguage);
    },
    getAdressFromCoords({
      state,
      commit
    }, coords) {
      $.ajax({

        url: `https://maps.googleapis.com/maps/api/geocode/json?latlng=${coords.lat},${coords.lng}&key=${state.apiKey}&language=${state.language}`,
        type: 'GET',
        dataType: 'json'
      }).done((res) => {
        if (res.status == 'OK') {
          localStorage.setItem('geoData', JSON.stringify(res.results[0]));
        }
      });
    },
    getTimeZone({
      state,
      commit
    }) {
      let currTime = Math.round(new Date().getTime() / 1000);
      $.ajax({
        url: `https://maps.googleapis.com/maps/api/timezone/json?location=${state.location.lat},${state.location.lng}&timestamp=${currTime}&key=${state.apiKey}`,
        type: 'GET',
        dataType: 'json'
      }).done((res) => {
        if (res.status == 'OK') {
          localStorage.setItem('timeZone', JSON.stringify(res.timeZoneId));
        }
      });
    }
  }
}

export default googleData;
