import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/api'

const MOCK_COURSES = [
  {
    id: 1, level: 'A1', name: 'Deutsch A1 – المبتدئ',
    description: 'ابدئي من الصفر — حروف، أرقام، جمل أساسية',
    duration_weeks: 8, sessions_per_week: 2, session_duration_minutes: 60, price: 800,
    schedule: [{ day: 'السبت', time: '7:00 م' }, { day: 'الثلاثاء', time: '7:00 م' }],
    learning_outcomes: ['التعارف وتقديم النفس', 'الأرقام والتواريخ', 'الأوامر والجمل البسيطة', 'الألوان والأشياء اليومية'],
    testimonials: [{ id: 1, student_name: 'سارة أحمد', body: 'بدأت من الصفر وبعد 8 أسابيع بقيت أقدر أتكلم جمل كاملة.', rating: 5 }]
  },
  {
    id: 2, level: 'A2', name: 'Deutsch A2 – الأساسي',
    description: 'محادثة يومية وتوسيع المفردات',
    duration_weeks: 8, sessions_per_week: 2, session_duration_minutes: 60, price: 900,
    schedule: [{ day: 'الأحد', time: '8:00 م' }, { day: 'الأربعاء', time: '8:00 م' }],
    learning_outcomes: ['وصف الأماكن والأشخاص', 'التسوق والمواصلات', 'الحديث عن الماضي (Perfekt)', 'فهم نصوص قصيرة'],
    testimonials: [{ id: 2, student_name: 'مريم خالد', body: 'نور بتشرح القواعد بطريقة ما توقعتيش إنها تبقى سهلة.', rating: 5 }]
  },
  {
    id: 3, level: 'B1', name: 'Deutsch B1 – المتوسط',
    description: 'قواعد متقدمة وفهم نصوص أطول',
    duration_weeks: 10, sessions_per_week: 2, session_duration_minutes: 75, price: 1100,
    schedule: [{ day: 'الاثنين', time: '7:30 م' }, { day: 'الخميس', time: '7:30 م' }],
    learning_outcomes: ['الجمل الفرعية المعقدة (Nebensätze)', 'كتابة رسائل رسمية', 'فهم نصوص إخبارية', 'التعبير عن الرأي'],
    testimonials: [{ id: 3, student_name: 'دينا يوسف', body: 'وصلت B1 وبقيت قادرة أفهم الفيديوهات الألمانية بدون ترجمة.', rating: 5 }]
  },
  {
    id: 4, level: 'B2', name: 'Deutsch B2 – المتقدم',
    description: 'طلاقة في المحادثة والكتابة',
    duration_weeks: 12, sessions_per_week: 2, session_duration_minutes: 90, price: 1300,
    schedule: [{ day: 'الثلاثاء', time: '9:00 م' }, { day: 'الجمعة', time: '9:00 م' }],
    learning_outcomes: ['الكتابة الأكاديمية والمهنية', 'فهم المحاضرات والبرامج الوثائقية', 'المحادثة بطلاقة', 'التحضير لاختبار Goethe B2'],
    testimonials: [{ id: 4, student_name: 'نورهان علي', body: 'اجتزت اختبار Goethe B2 من أول مرة! مش كنت هقدر من غير كورسات نور.', rating: 5 }]
  },
]

export const MOCK_QUESTIONS = [
  { id: 1, question_text: 'Wie heißt du? — ما معنى هذه الجملة؟', options: { a: 'كيف حالك؟', b: 'ما اسمك؟', c: 'من أين أنتِ؟', d: 'كم عمرك؟' }, order: 1 },
  { id: 2, question_text: 'أكملي: Ich ___ aus Ägypten.', options: { a: 'bin', b: 'bist', c: 'komme', d: 'heißt' }, order: 2 },
  { id: 3, question_text: 'ما معنى "Guten Morgen"؟', options: { a: 'مساء الخير', b: 'صباح الخير', c: 'إلى اللقاء', d: 'شكراً' }, order: 3 },
  { id: 4, question_text: 'أي رقم يمثل "zwanzig"؟', options: { a: '12', b: '20', c: '22', d: '200' }, order: 4 },
  { id: 5, question_text: 'اختاري الصيغة الصحيحة: Ich ___ gestern ins Kino gegangen.', options: { a: 'habe', b: 'hatte', c: 'bin', d: 'war' }, order: 5 },
  { id: 6, question_text: 'ما المعنى الصحيح لـ "Wo wohnst du?"', options: { a: 'ما مهنتك؟', b: 'أين تسكنين؟', c: 'كيف تذهبين؟', d: 'متى تعودين؟' }, order: 6 },
  { id: 7, question_text: 'أكملي: Das ist ___ Mann. (رجل محدد)', options: { a: 'ein', b: 'eine', c: 'der', d: 'die' }, order: 7 },
  { id: 8, question_text: 'اختاري الصحيح: Obwohl es regnet, ___ ich spazieren.', options: { a: 'gehe', b: 'gehst', c: 'geht', d: 'gehen' }, order: 8 },
  { id: 9, question_text: 'ما معنى "Konjunktiv II"؟', options: { a: 'صيغة الأمر', b: 'الماضي البسيط', c: 'صيغة الاحتمال/المجاملة', d: 'المبني للمجهول' }, order: 9 },
  { id: 10, question_text: 'أكملي: Das Buch, ___ ich lese, ist interessant.', options: { a: 'der', b: 'das', c: 'dem', d: 'dessen' }, order: 10 },
]

export const useCoursesStore = defineStore('courses', () => {
  const courses = ref([])
  const current = ref(null)
  const loading = ref(false)
  const error = ref(null)

  async function fetchAll() {
    loading.value = true
    error.value = null
    try {
      const { data } = await api.get('/courses')
      courses.value = data.data
    } catch {
      courses.value = MOCK_COURSES
    } finally {
      loading.value = false
    }
  }

  async function fetchOne(id) {
    loading.value = true
    error.value = null
    try {
      const { data } = await api.get(`/courses/${id}`)
      current.value = data.data
    } catch {
      current.value = MOCK_COURSES.find(c => c.id === Number(id)) || null
      if (!current.value) error.value = 'الكورس غير موجود'
    } finally {
      loading.value = false
    }
  }

  function getCourseByLevel(level) {
    return courses.value.find(c => c.level === level)
  }

  return { courses, current, loading, error, fetchAll, fetchOne, getCourseByLevel }
})
