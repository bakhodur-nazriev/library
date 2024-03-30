export function getFormData(data) {
    const formData = new FormData();

    for (const key in data) {
        if (Array.isArray(data[key])) {
            data[key].forEach((item) => {
                formData.append(`${key}[]`, item);
            });
        } else {
            formData.append(key, data[key]);
        }
    }

    return formData;
}