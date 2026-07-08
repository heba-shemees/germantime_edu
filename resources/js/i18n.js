import { ref } from "vue";

const lang = ref(localStorage.getItem("lang") || "ar");

export const translations = {
    ar: {
        // Navbar
        nav_courses: "الكورسات",
        nav_private: "حصص خاصة",
        nav_about: "عني",
        nav_contact: "تواصل معنا",
        nav_test: "ابدأ اختبار المستوى",

        // Hero
        hero_badge: "أكثر من 200 طالب بيتعلموا معنا",
        hero_title_1: "اتعلم الألماني",
        hero_title_2: "بطريقة سهلة وعملية",
        hero_desc: "كورسات من A1 حتى B2 — أونلاين — مع معلم متخصص",
        hero_cta: "ابدأ اختبار تحديد المستوى",
        hero_courses: "شوف الكورسات",

        // Stats
        stat_students: "طالب سعيد",
        stat_levels: "مستويات متاحة",
        stat_satisfaction: "نسبة رضا",
        stat_experience: "سنوات خبرة",

        // Courses
        courses_title: "الكورسات المتاحة",
        courses_subtitle: "من المبتدئ لحد المتقدم",
        course_details: "تفاصيل الكورس",
        currency: "جنيه",
        all_courses: "شوف كل الكورسات",
        book_now: "احجز الآن ←",
        level_filter_all: "الكل",

        // Course detail
        course_back: "← كل الكورسات",
        course_weeks: "أسبوع",
        course_sessions: "حصص / أسبوع",
        course_minutes: "دقيقة",
        course_online: "أونلاين",
        course_outcomes: "🎯 هتتعلم إيه؟",
        course_ready: "جاهز تبدأ؟",
        course_ready_desc: "احجز مكانك دلوقتي قبل ما الأماكن تخلص",

        // Testimonials
        testimonials_title: "ماذا قال الطلاب؟",
        testimonials_subtitle: "تجارب حقيقية من طلاب عملوا كورسات معنا",

        // CTA
        cta_title: "مستنياك! ابدأ رحلتك مع الألماني",
        cta_desc: "اعمل اختبار المستوى دلوقتي مجاناً",
        cta_btn: "ابدأ اختبار تحديد المستوى ←",

        // Start
        start_title: "مش عارف مستواك في الألماني؟",
        start_desc: "اعمل اختبار تحديد المستوى اللي هياخد منك 5 دقايق بس!",
        start_btn: "ابدأ الاختبار ←",
        start_free: "مجاني تماماً — مش محتاج تسجل",
        start_know: "أو لو عارف مستواك، ادخل على الكورس مباشرة:",
        benefit_1: "10 أسئلة بس",
        benefit_2: "نتيجة فورية",
        benefit_3: "توصية بالكورس",

        // Test
        test_title: "اختبار تحديد المستوى",
        test_question: "سؤال",
        test_of: "من",
        test_answered: "تم الإجابة",
        test_prev: "← السابق",
        test_next: "التالي →",
        test_submit: "سلّم الاختبار ✓",
        test_submitting: "جاري الإرسال...",
        test_loading: "بيتحمل الاختبار...",

        // Test Result
        result_title: "نتيجة اختبارك",
        result_score: "نقطة من 100",
        result_cta: "شوف كورس",
        result_retry: "أعد الاختبار",
        result_no: "مفيش نتيجة!",
        result_start: "ابدأ الاختبار",
        level_a1: "مبتدئ تماماً",
        level_a2: "مبتدئ متقدم",
        level_b1: "متوسط",
        level_b2: "متقدم",
        desc_a1: "هتبدأ من الأساس — كورس A1 هو الأنسب لك",
        desc_a2: "عندك أساس كويس! كورس A2 هيوسع مفرداتك",
        desc_b1: "مستواك كويس! كورس B1 هيوصلك لمستوى أعلى",
        desc_b2: "ممتاز! كورس B2 هيوصلك للطلاقة",

        // Booking
        booking_title: "احجز الآن",
        booking_desc: "املأ البيانات وهنتواصل معاك على واتساب",
        booking_name: "الاسم الكامل",
        booking_name_ph: "اسمك هنا",
        booking_phone: "رقم الواتساب",
        booking_phone_ph: "01XXXXXXXXX",
        booking_level: "المستوى",
        booking_notes: "ملاحظات (اختياري)",
        booking_notes_ph: "أي سؤال أو ملاحظة...",
        booking_btn: "احجز الآن ←",
        booking_note: "بعد الحجز هنوصلك تفاصيل الدفع على واتساب",
        select_level: "اختار المستوى",
        level_private: "حصة خاصة",
        course_available: "متاح - التسجيل مفتوح",
        course_full: "الكورس مكتمل",
        course_full_notice:
            "عذراً، تم اكتمال جميع المقاعد المتاحة لهذا المستوى.",

        // Payment
        payment_title: "تم الحجز!",
        payment_desc: "خطوة أخيرة — ادفع عشان يكتمل التسجيل",
        payment_warning: "⚠️ بعد الدفع",
        payment_step1: "ابعت Screenshot من الإيصال على واتساب",
        payment_step2: "اكتب اسمك ومستواك في الرسالة",
        payment_step3: "هنرد عليك خلال 24 ساعة بلينك الكلاس",
        payment_wa: "💬 ابعت الإيصال على واتساب",
        copy: "انسخ",
        copied: "✓ تم النسخ",

        // About
        about_badge: "معلم ألماني متخصص",
        about_title: "Frau Heba",
        about_desc:
            "معلم لغة ألمانية شغوف بتعليم اللغات بطريقة سهلة وعملية. هدفي إن كل طالب يحس إن الألماني مش صعب — بس محتاج الطريقة الصح.",
        about_style_title: "أسلوب التدريس",
        about_cta: "ابدأ اختبار المستوى ←",

        // Private
        private_title: "الحصص الخاصة",
        private_desc: "اهتمام كامل بيك — مناهج مخصصة لمستواك وأهدافك",
        private_btn: "احجز حصة خاصة ←",
        private_note: "هنتواصل معاك خلال 24 ساعة لتأكيد الموعد",

        // Contact
        contact_title: "تواصل معنا",
        contact_desc: "هنرد عليك في أسرع وقت ممكن",
        contact_cta: "اعمللك اختبار مستوى مجاني ←",
        contact_no_idea: "مش عارف تبدأ من فين؟",

        // Footer
        footer_desc: "تعلم الألماني بطريقة سهلة وعملية مع كورسات من A1 حتى B2",
        footer_links: "روابط سريعة",
        footer_contact: "تواصل معنا",
        footer_rights: "جميع الحقوق محفوظة",
        whatsapp_direct: "واتساب مباشر",
    },

    de: {
        // Navbar
        nav_courses: "Kurse",
        nav_private: "Einzelstunden",
        nav_about: "Über mich",
        nav_contact: "Kontakt",
        nav_test: "Einstufungstest starten",

        // Hero
        hero_badge: "Mehr als 200 glückliche Schüler",
        hero_title_1: "Deutsch lernen",
        hero_title_2: "einfach und praktisch",
        hero_desc:
            "Kurse von A1 bis B2 — online — mit einem spezialisierten Lehrer",
        hero_cta: "Einstufungstest starten",
        hero_courses: "Kurse ansehen",

        // Stats
        stat_students: "glückliche Schüler",
        stat_levels: "verfügbare Niveaus",
        stat_satisfaction: "Zufriedenheitsrate",
        stat_experience: "Jahre Erfahrung",

        // Courses
        courses_title: "Verfügbare Kurse",
        courses_subtitle: "Von Anfänger bis Fortgeschrittene",
        course_details: "Kursdetails",
        currency: "EGP",
        all_courses: "Alle Kurse ansehen",
        book_now: "Jetzt buchen →",
        level_filter_all: "Alle",

        // Course detail
        course_back: "← Alle Kurse",
        course_weeks: "Wochen",
        course_sessions: "Stunden / Woche",
        course_minutes: "Minuten",
        course_online: "Online",
        course_outcomes: "🎯 Was werden Sie lernen?",
        course_ready: "Bereit anzufangen?",
        course_ready_desc: "Buchen Sie jetzt Ihren Platz",

        // Testimonials
        testimonials_title: "Was sagen die Schüler?",
        testimonials_subtitle: "Echte Erfahrungen von echten Schülern",

        // CTA
        cta_title: "Wir warten auf Sie! Starten Sie Ihre Deutschreise",
        cta_desc: "Machen Sie jetzt kostenlos den Einstufungstest",
        cta_btn: "Einstufungstest starten →",

        // Start
        start_title: "Kennen Sie Ihr Deutschniveau nicht?",
        start_desc: "Machen Sie unseren Einstufungstest — nur 5 Minuten!",
        start_btn: "Test starten →",
        start_free: "Kostenlos — keine Registrierung erforderlich",
        start_know:
            "Oder wenn Sie Ihr Niveau kennen, gehen Sie direkt zum Kurs:",
        benefit_1: "Nur 10 Fragen",
        benefit_2: "Sofortiges Ergebnis",
        benefit_3: "Kursempfehlung",

        // Test
        test_title: "Einstufungstest",
        test_question: "Frage",
        test_of: "von",
        test_answered: "beantwortet",
        test_prev: "← Zurück",
        test_next: "Weiter →",
        test_submit: "Test abgeben ✓",
        test_submitting: "Wird gesendet...",
        test_loading: "Test wird geladen...",

        // Test Result
        result_title: "Ihr Testergebnis",
        result_score: "Punkte von 100",
        result_cta: "Kurs ansehen",
        result_retry: "Test wiederholen",
        result_no: "Kein Ergebnis!",
        result_start: "Test starten",
        level_a1: "Absoluter Anfänger",
        level_a2: "Fortgeschrittener Anfänger",
        level_b1: "Mittelstufe",
        level_b2: "Oberstufe",
        desc_a1:
            "Sie beginnen von Grund auf — Kurs A1 ist der richtige für Sie",
        desc_a2:
            "Sie haben eine gute Basis! Kurs A2 erweitert Ihren Wortschatz",
        desc_b1:
            "Ihr Niveau ist gut! Kurs B1 bringt Sie auf ein höheres Niveau",
        desc_b2: "Ausgezeichnet! Kurs B2 bringt Sie zur Fließendheit",

        // Booking
        booking_title: "Jetzt buchen",
        booking_desc:
            "Füllen Sie das Formular aus — wir melden uns per WhatsApp",
        booking_name: "Vollständiger Name",
        booking_name_ph: "Ihr Name hier",
        booking_phone: "WhatsApp-Nummer",
        booking_phone_ph: "+49XXXXXXXXX",
        booking_level: "Niveau",
        booking_notes: "Anmerkungen (optional)",
        booking_notes_ph: "Fragen oder Anmerkungen...",
        booking_btn: "Jetzt buchen →",
        booking_note:
            "Nach der Buchung erhalten Sie die Zahlungsdetails per WhatsApp",
        select_level: "Niveau auswählen",
        level_private: "Einzelstunde",
        course_available: "Verfügbar – Anmeldung offen",
        course_full: "Kurs ausgebucht",
        course_full_notice:
            "Entschuldigung, alle Plätze für dieses Niveau sind bereits belegt.",

        // Payment
        payment_title: "Buchung bestätigt!",
        payment_desc:
            "Letzter Schritt — Bezahlen Sie, um die Registrierung abzuschließen",
        payment_warning: "⚠️ Nach der Zahlung",
        payment_step1: "Senden Sie einen Screenshot der Quittung per WhatsApp",
        payment_step2:
            "Schreiben Sie Ihren Namen und Ihr Niveau in die Nachricht",
        payment_step3:
            "Wir antworten innerhalb von 24 Stunden mit dem Kurslink",
        payment_wa: "💬 Quittung per WhatsApp senden",
        copy: "Kopieren",
        copied: "✓ Kopiert",

        // About
        about_badge: "Spezialisierter Deutschlehrer",
        about_title: "Frau Heba",
        about_desc:
            "Ich bin ein leidenschaftlicher Deutschlehrer, der Sprachen auf einfache und praktische Weise unterrichtet. Mein Ziel ist es, dass jeder Schüler fühlt, dass Deutsch nicht schwer ist — es braucht nur die richtige Methode.",
        about_style_title: "Unterrichtsstil",
        about_cta: "Einstufungstest starten →",

        // Private
        private_title: "Einzelstunden",
        private_desc:
            "Volle Aufmerksamkeit für Sie — maßgeschneiderte Inhalte für Ihr Niveau und Ihre Ziele",
        private_btn: "Einzelstunde buchen →",
        private_note:
            "Wir melden uns innerhalb von 24 Stunden zur Terminbestätigung",

        // Contact
        contact_title: "Kontakt",
        contact_desc: "Wir antworten so schnell wie möglich",
        contact_cta: "Kostenlosen Einstufungstest machen →",
        contact_no_idea: "Wissen Sie nicht, wo Sie anfangen sollen?",

        // Footer
        footer_desc:
            "Deutsch lernen — einfach und praktisch — Kurse von A1 bis B2",
        footer_links: "Schnelllinks",
        footer_contact: "Kontakt",
        footer_rights: "Alle Rechte vorbehalten",
        whatsapp_direct: "WhatsApp direkt",
    },
};

export function useLang() {
    function t(key) {
        return (
            translations[lang.value]?.[key] || translations["ar"][key] || key
        );
    }

    function setLang(l) {
        lang.value = l;
        localStorage.setItem("lang", l);
        document.documentElement.dir = l === "ar" ? "rtl" : "ltr";
        document.documentElement.lang = l;
    }

    document.documentElement.dir = lang.value === "ar" ? "rtl" : "ltr";

    return { lang, t, setLang };
}
