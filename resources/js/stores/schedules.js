import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/api'

export const useSchedulesStore = defineStore('schedules', () => {
  const schedules = ref([])
  const loading = ref(false)
  const error = ref(null)

  async function fetchAll(filters = {}) {
    loading.value = true
    error.value = null
    try {
      const { data } = await api.get('/v1/schedules', { params: filters })
      schedules.value = data.data
    } catch {
      // Mock data for frontend development
      schedules.value = MOCK_SCHEDULES
    } finally {
      loading.value = false
    }
  }

  return { schedules, loading, error, fetchAll }
})

const MOCK_SCHEDULES = [
  { id:1, title:'A1 - مجموعة صباحية', course:{ id:1, level:'A1', name:'المبتدئ' }, day_of_week:'Saturday', start_time:'10:00', end_time:'11:00', start_date:'2026-06-01', end_date:'2026-07-26', max_seats:10, available_seats:5, is_full:false },
  { id:2, title:'A1 - مجموعة مسائية', course:{ id:1, level:'A1', name:'المبتدئ' }, day_of_week:'Tuesday', start_time:'19:00', end_time:'20:00', start_date:'2026-06-03', end_date:'2026-07-29', max_seats:8, available_seats:0, is_full:true },
  { id:3, title:'A2 - مجموعة مسائية', course:{ id:2, level:'A2', name:'الأساسي' }, day_of_week:'Sunday', start_time:'20:00', end_time:'21:00', start_date:'2026-06-07', end_date:'2026-08-02', max_seats:10, available_seats:3, is_full:false },
  { id:4, title:'B1 - مجموعة مسائية', course:{ id:3, level:'B1', name:'المتوسط' }, day_of_week:'Monday', start_time:'19:30', end_time:'20:45', start_date:'2026-06-08', end_date:'2026-08-17', max_seats:8, available_seats:8, is_full:false },
  { id:5, title:'B2 - مجموعة ليلية', course:{ id:4, level:'B2', name:'المتقدم' }, day_of_week:'Wednesday', start_time:'21:00', end_time:'22:30', start_date:'2026-06-10', end_date:'2026-09-02', max_seats:6, available_seats:2, is_full:false },
]
