const state = {
  customer:"loading"
};

const mutations = {
  setCustomer(state, value)
  {
    state.customer = value;
  }
};

const actions = {
  getCustomers(vuexContext, value){
    const options = {
      withCredentials: true,
      headers: {
        //'Content-Type': 'application/x-www-form-urlencoded',
        'Accept':'application/json',
        'Content-Type':'application/json',
        'Authorization' : 'Bearer '+vuexContext.getters.getAuthToken
      },
    };

    if(value.search != null)
    {
      vuexContext.commit("setCustomerSearch",value.search);
      if(value.searchType != null)
      {
        vuexContext.commit("setCustomerSearchType",value.searchType);
      }
    }

    vuexContext.commit("setCustomer","loading");

    let url = "/admin/user?page="+value.page+(value.perPage != null ? "&total="+value.perPage : "")+(value.search != null ? "&search="+value.search : "");
    this.$axios.get(url,options)
        .then(response => {
          vuexContext.commit("setCustomer",response.data);
        })
        .catch((err) => {
          console.log(err);
          vuexContext.dispatch("statusControl",err.response.status);
        })
  },
  getCustomerData(vuexContext, value){
    const options = {
      withCredentials: true,
      headers: {
        //'Content-Type': 'application/x-www-form-urlencoded',
        'Accept':'application/json',
        'Content-Type':'application/json',
        'Authorization' : 'Bearer '+ vuexContext.getters.getAuthToken
      },
    };

    vuexContext.commit("setCustomer","loading");
    this.$axios.get("/user/"+value,options)
        .then(response => {
          vuexContext.commit("setCustomer",response.data);
        })
        .catch((err) => {
          console.log(err);
          vuexContext.dispatch("statusControl",err.response.status);
        })
  },
  addCustomer(vuexContext, value){
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

      return this.$axios.post("/user",options,headers)
      .then(response => {
        console.log(response.data);
        if(response.status == 201)
        {
          vuexContext.commit("setCustomer",{});
          return response.data;

        }
        //vuexContext.commit("setOrder",response.data);
      })
      .catch((err) => {
          console.log(err);
          vuexContext.dispatch("statusControl",err.response.status);
          return err.response.data.errors;
        })

  },
  updateCustomer(vuexContext, value){
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

    return this.$axios.put("/user/"+value.id,options,headers)
    .then(response => {
      if(response.data == 200)
        {
          return response.data;
        }
    })
    .catch((err) => {
          console.log(err);
          vuexContext.dispatch("statusControl",err.response.status);
          return err.response.data.errors;
        })
  },
  deleteCustomer(vuexContext, value){
    const headers = {
      withCredentials: true,
      headers: {
        'Accept':'application/json',
        'Content-Type':'application/json',
        'Authorization' : 'Bearer '+vuexContext.getters.getAuthToken
       }

    };

    return this.$axios.delete("/admin/user/"+value,headers)
    .then(response => {
      if(response.status == 204)
        {
          return response.data;
        }
    })
    .catch((err) => {
          console.log(err);
          vuexContext.dispatch("statusControl",err.response.status);
        })
  }
};

const getters = {
  getCustomer(state)
  {
    return state.customer;
  }
};

export default{
state,
mutations,
actions,
getters
}
