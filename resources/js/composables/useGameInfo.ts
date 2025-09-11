import { ref, onMounted, onBeforeUnmount } from 'vue'

const gameDay = ref(0)
const gameTime = ref('00:00')
let intervalId: any = null

export function useGameInfo() {
    const fetchGameInfo = async () => {
        try {
            const res = await fetch('/info')
            const data = await res.json()
            gameDay.value = data.day
            gameTime.value = data.time
        } catch (error) {
            console.error('Failed to fetch game info', error)
        }
    }

    const startPolling = () => {
        fetchGameInfo() // initial fetch
        intervalId = setInterval(fetchGameInfo, 2000)
    }

    const stopPolling = () => {
        clearInterval(intervalId)
    }

    onMounted(startPolling)
    onBeforeUnmount(stopPolling)

    return { gameDay, gameTime, fetchGameInfo, startPolling, stopPolling }
}
