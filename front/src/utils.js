export function getFormData(data) {
    const formData = new FormData();

    for (const key in data) {
        if (Array.isArray(data[key])) {
            // If the value is an array, append each item separately
            data[key].forEach((item) => {
                formData.append(`${key}[]`, item);
            });
        } else {
            // Otherwise, append the value as is
            formData.append(key, data[key]);
        }
    }

    return formData;
}