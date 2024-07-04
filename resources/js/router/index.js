import { setupLayouts } from 'virtual:generated-layouts'
import { createRouter, createWebHistory } from 'vue-router'
import routes from '~pages'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    ...setupLayouts(routes),
  ],
})

// Docs: https://router.vuejs.org/guide/advanced/navigation-guards.html#global-before-guards
router.beforeEach(to => {
  if (to.name == 'login')
    return;

  const isLoggedIn = !!(localStorage.getItem('userData') && localStorage.getItem('accessToken'))
  if (!isLoggedIn)
    return { name: 'login' }
  
})
export default router
