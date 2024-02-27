import { createI18n } from 'vue-i18n'

async function loadLocaleMessages() {
    const localesContext = import.meta.glob('./locales/*.json')

    const messages = {}
    const importPromises = []

    for (const key in localesContext) {
        if (localesContext.hasOwnProperty(key)) {
            const matched = key.match(/([A-Za-z0-9-_]+)\./i)

            if (matched && matched.length > 1) {
                const locale = matched[1]
                const module = await localesContext[key]()

                messages[locale] = module.default
                importPromises.push(module)
            }
        }
    }

    await Promise.all(importPromises)
    return messages
}

const i18n = createI18n({
    locale: 'ru',
    fallbackLocale: 'en',
    messages: await loadLocaleMessages()
})

export default i18n