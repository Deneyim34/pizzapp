export default async function(context){

  if(process.client)
  {
    context.store.dispatch("initAuth");
  }
  else{
    context.store.dispatch("initAuth", context.req);
  }


  if(context.store.getters.getAuthToken == null)
  {
    return context.store.dispatch("setLogout","/");
  }
}
