import Cookie from 'js-cookie';

const state = {
  AuthToken : null,
  AuthAdminToken : null,
  userData : null,
  adminAccess : false,
  Access : false
};

const mutations = {
  setAuthToken(state,value)
  {
    state.AuthToken = value;
  },
  setUserData(state,value)
  {
    state.userData = value;
  },
  setAdminAccess(state,value)
  {
    state.adminAccess = value;
  },
  setAccess(state,value)
  {
    state.Access = value;
  }
};

const actions = {
  nuxtServerInit ({ commit }, { req }) {

  },
  initAuth(vuexContext, req)
  {
    let token;
    if(req)
    {
        if(!req.headers.cookie)
        {
          return;
        }

        token = req.headers.cookie.split(';').find(c=>c.trim().startsWith('user-token='));

        if(token)
        {
          token = token.split('=')[1];
        }
    }
    else
    {
        token = Cookie.get('user-token');
        if(!token)
        {
          return;
        }

    }
    vuexContext.commit("setAuthToken",token);
  },
  getCookie (vuexContext, value) {
    let strCookie = new RegExp('' + value.cookieName + '[^;]+').exec(value.stringCookie)[0]
    return unescape(strCookie ? strCookie.toString().replace(/^[^=]+./, '') : '')
  },
  login(vuexContext, value)
  {
    const options = {
      withCredentials: true,
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      username : value.username,
      password : value.password
    };

    return this.$axios.post("/login",options)
    .then(response => {
      if(response.status == 200)
      {
        let token = response.data.access_token;
        vuexContext.commit("setAuthToken",token);
        localStorage.setItem('user-token',token);
        Cookie.set("user-token",token);
        this.$router.push(value.redirect);
        return "";
      }

    })
    .catch((err) => {
      return err.response.data;
      console.log(err);
    })
  },
  register(vuexContext, value){
      const options = {
        ...value
      };

      const headers = {
        withCredentials: true,
        headers: {
          'Accept':'application/json',
          'Content-Type':'application/json',
          'Authorization' : 'Bearer '+vuexContext.getters.getAuthToken
         }

      };

      return this.$axios.post("/register",options,headers)
      .then(response => {
        if(response.status == 201)
        {
          return response;

        }
        //vuexContext.commit("setOrder",response.data);
      })
      .catch((err) => {
          console.log(err);
          vuexContext.dispatch("statusControl",err.response.status);
          return err.response.data.errors;
        })

  },
  authCheck(vuexContext, value)
  {
    const options = {
      withCredentials: true,
      headers: {
        //'Content-Type': 'application/x-www-form-urlencoded',
        'Accept':'application/json',
        'Content-Type':'application/json',
        'Authorization' : 'Bearer '+vuexContext.getters.getAuthToken
      },
    };

    if(value == "/admin/")
    {
      vuexContext.commit("setAdminAccess", false);

      Cookie.set("AdminAccess",false);
    }else{
      vuexContext.commit("setAccess", false);
    }


     this.$axios.get(value+"authCheck",options)
    .then(response => {
      if(response.status != 200)
      {
        return vuexContext.dispatch("setLogout",value);
      }
      else
      {
        if(value == "/admin/")
        {
          vuexContext.commit("setAdminAccess", true);
        }else{
          vuexContext.commit("setAccess", true);
        }
        console.log(response.data);
        vuexContext.commit("setUserData",response.data);
        return;// this.$router.push(value);
      }
    })
    .catch((err) => {
      console.log(err);
    })
  },
  logout(vuexContext, value)
  {
    const options = {

    };
    const headers = {
      withCredentials: true,
      //'Content-Type': 'application/x-www-form-urlencoded',
      headers: {
        'Accept':'application/json',
        'Content-Type':'application/json',
        'Authorization' : 'Bearer '+vuexContext.getters.getAuthToken
       }

    };
    this.$axios.post("/logout",{options},headers)
    .then(response => {
      if(response.data == "ok")
      {
        localStorage.removeItem("user-token");
        Cookie.remove("user-token");
        vuexContext.commit("setAuthToken",null);
        this.$router.push(value);
      }
    })
    .catch((err) => {
      console.log(err);
    })
  },
  setLogout(vuexContext, value)
  {
    const options = {

    };

    const headers = {
      withCredentials: true,
      //'Content-Type': 'application/x-www-form-urlencoded',
      headers: {
        'Accept':'application/json',
        'Content-Type':'application/json',
        'Authorization' : 'Bearer '+vuexContext.getters.getAuthToken
       }

    };


    Cookie.remove("user-token");
    vuexContext.commit("setAuthToken",null);

    this.$axios.post("/logout",{options},headers)
    .then(response => {
      if(response.data == "ok")
      {

      }
    })
    .catch((err) => {
      console.log(err);
    })

    this.$router.push(value+"login");
  },
  statusControl(vuexContext, value)
  {
    if(value == 401 || value == 403)
    {
      return vuexContext.dispatch("setLogout",vuexContext.getters.getRedirectURL);
    }
  }
};

const getters = {
  getAuthToken(state)
  {
      return state.AuthToken;
  },
  getUserData(state)
  {
    return state.userData;
  },
  getAdminAccess(state)
  {
    return state.adminAccess;
  },
  getAccess(state)
  {
    return state.Access;
  }
};

export default{
state,
mutations,
actions,
getters
}
