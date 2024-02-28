export const getFormData = (options) => {
    const formData = new FormData()
    for (const key in options) {
        formData.append(key, options[key])
    }

    return formData;
};