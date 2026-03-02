export function getSessionId() {
    let id = localStorage.getItem('casio_session_id')
    if (!id) {
        id = crypto.randomUUID()
        localStorage.setItem('casio_session_id', id)
    }
    return id
}
