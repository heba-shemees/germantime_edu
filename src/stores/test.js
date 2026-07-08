import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/api'
import { MOCK_QUESTIONS } from './courses'

const CORRECT_ANSWERS = { 1:'b', 2:'c', 3:'b', 4:'b', 5:'c', 6:'b', 7:'c', 8:'a', 9:'c', 10:'b' }

export const useTestStore = defineStore('test', () => {
  const questions = ref([])
  const answers = ref({})
  const result = ref(null)
  const loading = ref(false)
  const submitted = ref(false)

  const totalQuestions = computed(() => questions.value.length)
  const answeredCount = computed(() => Object.keys(answers.value).length)
  const progress = computed(() =>
    totalQuestions.value ? Math.round((answeredCount.value / totalQuestions.value) * 100) : 0
  )
  const allAnswered = computed(() => answeredCount.value === totalQuestions.value)

  async function loadQuestions() {
    loading.value = true
    try {
      const { data } = await api.get('/test/questions')
      questions.value = data.data
    } catch {
      // Use mock questions when backend is unavailable
      questions.value = MOCK_QUESTIONS
    } finally {
      loading.value = false
    }
  }

  function setAnswer(questionId, option) {
    answers.value = { ...answers.value, [questionId]: option }
  }

  async function submit() {
    loading.value = true
    try {
      // Try API first
      const { data } = await api.post('/test/submit', { answers: answers.value })
      result.value = data.data
    } catch {
      // Calculate score locally when backend is unavailable
      result.value = calculateLocally()
    } finally {
      submitted.value = true
      sessionStorage.setItem('testResult', JSON.stringify(result.value))
      loading.value = false
    }
    return result.value
  }

  function calculateLocally() {
    const total = Object.keys(CORRECT_ANSWERS).length * 10
    let earned = 0
    for (const [qId, selected] of Object.entries(answers.value)) {
      if (CORRECT_ANSWERS[Number(qId)] === selected) earned += 10
    }
    const score = Math.round((earned / total) * 100)
    const level = score <= 30 ? 'A1' : score <= 50 ? 'A2' : score <= 70 ? 'B1' : 'B2'
    const courseMap = { A1: 1, A2: 2, B1: 3, B2: 4 }
    return { score, level, course_id: courseMap[level] }
  }

  function reset() {
    answers.value = {}
    result.value = null
    submitted.value = false
    questions.value = []
  }

  function loadResultFromSession() {
    const saved = sessionStorage.getItem('testResult')
    if (saved) result.value = JSON.parse(saved)
    return result.value
  }

  return {
    questions, answers, result, loading, submitted,
    totalQuestions, answeredCount, progress, allAnswered,
    loadQuestions, setAnswer, submit, reset, loadResultFromSession
  }
})
