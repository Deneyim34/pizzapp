import Vuex from 'vuex';
import Order from './order';
import Product from './product';
import Customer from './customer';
import Cart from './cart';
import Auth from './auth';
import adminAuth from './adminAuth';


const createStore = () => {
    return new Vuex.Store({
        state : {
            isLogged : false,
            Statuses : null,
            Sizes : null,
            Countries : null,
            Cities : null,
            Districts : null,
            ModelData : null,
            AlertData : null,
            AlertShow : false,
            redirectURL : "/",
            search : {
              order:null,
              product:null,
              customer: null
            },
            searchType : {
              order:null,
              product:null,
              customer: null
            },
        },
        mutations : {
            setRedirectURL(state, value)
            {
                state.redirectURL = value;
            },
            setIsLogged(state, value)
            {
                state.isLogged = value;
            },
            setStatuses(state, value)
            {
                state.Statuses = value;
            },
            setSizes(state, value)
            {
                state.Sizes = value;
            },
            setCountries(state, value)
            {
                state.Countries = value;
            },
            setCities(state, value)
            {
                state.Cities = value;
            },
            setDistricts(state, value)
            {
                state.Districts = value;
            },
            setModelData(state, value)
            {
                state.ModelData = value;
            },
            setAlertData(state, value)
            {
                state.AlertData = value;
                state.AlertShow = true;
            },
            setAlertShow(state, value)
            {
                state.AlertShow = value;
            },
            setOrderSearch(state, value)
            {
              state.search.order = value;
            },
            setOrderSearchType(state, value)
            {
              state.searchType.order = value;
            },
            setProductSearch(state, value)
            {
              state.search.product = value;
            },
            setProductSearchType(state, value)
            {
              state.searchType.product = value;
            },
            setCustomerSearch(state, value)
            {
              state.search.customer = value;
            },
            setCustomerSearchType(state, value)
            {
              state.searchType.customer = value;
            }
        },
        actions : {

            nuxtServerInit ({ commit }, { req }) {

            },
            resetFilters(vuexContext,value)
            {
              vuexContext.commit("setOrderSearch",null);
              vuexContext.commit("setOrderSearchType",null);
              vuexContext.commit("setProductSearch",null);
              vuexContext.commit("setProductSearchType",null);
              vuexContext.commit("setCustomerSearch",null);
              vuexContext.commit("setCustomerSearchType",null);
            },
            getRedirectURL(vuexContext,value)
            {
              window.location.pathname.indexOf("/admin") !== -1 ? vuexContext.commit("setRedirectURL", "/admin/") : vuexContext.commit("setRedirectURL", "/");
            },
            getDatas(vuexContext,value)
            {
              const options = {
                withCredentials: true,
                headers: {
                  //'Content-Type': 'application/x-www-form-urlencoded',
                  'Accept':'application/json',
                  'Content-Type':'application/json',
                  'Authorization' : 'Bearer '+vuexContext.getters.getAuthToken
                },
                params:value.data
              };

              this.$axios.get(value.url, options)
              .then(response => {
                vuexContext.commit(value.mutationName,response.data.data);
              })
              .catch((err) => {
                console.log(err.response.status);
                vuexContext.dispatch("statusControl",err.response.status);
              })
            },
            setModelData(vuexContext, value)
            {
              vuexContext.commit("setModelData", value);
            },
            setAlertData(vuexContext, value)
            {
              vuexContext.commit("setAlertData", value);
            },
            setAlertShow(vuexContext, value)
            {
              vuexContext.commit("setAlertShow", value);
            }

        },
        getters : {
            getRedirectURL(state)
            {
                return state.redirectURL;
            },
            getIsLogged(state)
            {
                return state.isLogged;
            },
            getStatuses(state)
            {
                return state.Statuses;
            },
            getSizes(state)
            {
                return state.Sizes;
            },
            getCountries(state)
            {
                return state.Countries;
            },
            getCities(state)
            {
                return state.Cities;
            },
            getDistricts(state)
            {
                return state.Districts;
            },
            getModelData(state)
            {
                return state.ModelData;
            },
            getAlertData(state)
            {
                return state.AlertData;
            },
            getAlertShow(state)
            {
                return state.AlertShow;
            },
            getSearch(state)
            {
                return state.search;
            },
            getSearchType(state)
            {
                return state.searchType;
            },
            getOrderSearch(state)
            {
              return state.search.order;
            },
            getOrderSearchType(state)
            {
              return state.searchType.order;
            },
            getProductSearch(state)
            {
              return state.search.product;
            },
            getProductSearchType(state)
            {
              return state.searchType.product;
            },
            getCustomerSearch(state)
            {
              return state.search.customer;
            },
            getCustomerSearchType(state)
            {
              return state.searchType.customer;
            }
        },
        modules : {
          Order,
          Product,
          Customer,
          Cart,
          Auth,
          adminAuth
        }
    });
}

export default createStore;
