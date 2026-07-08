import { defineStore } from 'pinia'
import { ref, reactive } from 'vue'
import api from '@/api'

export const useBookingStore = defineStore('booking', () => {
  const booking = ref(null)
  const loading = ref(false)
  const errors = reactive({})

  async function submit(formData) {
    loading.value = true
    Object.keys(errors).forEach(k => delete errors[k])
    try {
      const { data } = await api.post('/bookings', formData)
      booking.value = data.data
    } catch (e) {
      if (e.errors) {
        Object.assign(errors, e.errors)
        throw e
      }
      // Backend unavailable — create local booking object
      booking.value = {
        id: Date.now(),
        reference: 'BK-' + Math.random().toString(36).substring(2,10).toUpperCase(),
        name: formData.name,
        whatsapp: formData.whatsapp,
        level: formData.level,
        status: 'pending',
        course: null,
      }
    } finally {
      sessionStorage.setItem('booking', JSON.stringify(booking.value))
      loading.value = false
    }
    return booking.value
  }

  function loadFromSession() {
    const saved = sessionStorage.getItem('booking')
    if (saved) booking.value = JSON.parse(saved)
    return booking.value
  }

  return { booking, loading, errors, submit, loadFromSession }
})
