import Cookie from 'js-cookie';

const state = {
  AuthAdminToken : null,
  adminData : null
};

const mutations = {
  setAuthAdminToken(state,value)
  {
    state.AuthAdminToken = value;
  },
  setAdminData(state,value)
  {
    state.adminData = value;
  }
};

const actions = {
  initAdminAuth(vuexContext, req)
  {
    let token;
    if(req)
    {
        if(!req.headers.cookie)
        {
          return;
        }

        token = req.headers.cookie.split(';').find(c=>c.trim().startsWith('admin-token='));

        if(token)
        {
          token = token.split('=')[1];
        }
    }
    else
    {
        token = Cookie.get('admin-token');
        if(!token)
        {
          return;
        }
    }

    vuexContext.commit("setAuthAdminToken",token);
  },
  setAdminLogout(vuexContext, value)
  {
    const options = {

    };

    const headers = {
      withCredentials: true,
      //'Content-Type': 'application/x-www-form-urlencoded',
      headers: {
        'Accept':'application/json',
        'Content-Type':'application/json',
        'Authorization' : 'Bearer '+vuexContext.getters.getAuthAdminToken
       }

    };

    //localStorage.removeItem("admin-token");
    Cookie.remove("admin-token");
    vuexContext.commit("setAuthAdminToken",null);
    vuexContext.commit("setAdminData",null);
    this.$router.push("/admin/login");

    this.$axios.post("/admin/logout",{options},headers)
    .then(response => {

    })
    .catch((err) => {
      console.log(err);
    })
  },
  adminLogin(vuexContext, value)
  {
    const options = {
      withCredentials: true,
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      username : value.username,
      password : value.password,
      type : value.type
    };

    this.$axios.post("/login",options)
    .then(response => {
      console.log(response.data)
      let token = response.data.access_token;
      localStorage.setItem('admin-token',token);
      Cookie.set("admin-token",token);
      vuexContext.commit("setAuthAdminToken",token);
      this.$router.push("/admin/");

    })
    .catch((err) => { console.log(err);})
  },
  adminAuthCheck(vuexContext, value)
  {
    const options = {
      withCredentials: true,
      headers: {
        //'Content-Type': 'application/x-www-form-urlencoded',
        'Accept':'application/json',
        'Content-Type':'application/json',
        'Authorization' : 'Bearer '+vuexContext.getters.getAuthAdminToken
      },
    };

    this.$axios.get("/admin/user",options)
    .then(response => {
      console.log(response.data)
      vuexContext.commit("setAdminData",response.data);
    })
    .catch((err) => {
      console.log(err);
      if(err.response.status == 401)
      {
        vuexContext.dispatch("setAdminLogout");
      }
    })
  },
  adminLogout(vuexContext, value)
  {
    const options = {

    };

    const headers = {
      withCredentials: true,
      //'Content-Type': 'application/x-www-form-urlencoded',
      headers: {
        'Accept':'application/json',
        'Content-Type':'application/json',
        'Authorization' : 'Bearer '+vuexContext.getters.getAuthAdminToken
       }

    };
    this.$axios.post("/admin/logout",{options},headers)
    .then(response => {
      if(response.data == "ok")
      {
        //localStorage.removeItem("admin-token");
        Cookie.remove("admin-token");
        vuexContext.commit("setAuthAdminToken",null);
        vuexContext.commit("setAdminData",null);
        this.$router.push("/admin/login");
      }
    })
    .catch((err) => {
      console.log(err);
    })
  }
};

const getters = {
  getAuthAdminToken(state)
  {
      return state.AuthAdminToken;
  },
  getAdminData(state)
  {
      return state.adminData;
  }
};

export default{
state,
mutations,
actions,
getters
}
