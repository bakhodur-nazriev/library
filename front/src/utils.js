export function getFormData(data) {
    const formData = new FormData();

    for (const key in data) {
        if (Array.isArray(data[key])) {
            data[key].forEach((item) => {
                formData.append(`${key}[]`, item);
            });
        } else {
            if (key === 'photo_link' && data[key] instanceof File) {
                formData.append(key, data[key]);
            } else {
                formData.append(key, data[key]);
            }
        }
    }

    return formData;
}