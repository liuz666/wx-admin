import Vue from 'vue'
import Router from 'vue-router'
import Home from '@/components/Home'
// import News from '@/components/News'
import Score from '@/components/Score'
import Sets from '@/components/Set'
import UserList from '@/components/UserList'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/home',
      name: 'home',
      component: Home
    },
    {
      path: '/score',
      name: 'score',
      component: Score
    },
    {
      path: '/set',
      name: 'set',
      component: Sets
    },
    {
      path: '/userList',
      name: 'userList',
      component: UserList
    }
  ],
  mode:'history'
})
