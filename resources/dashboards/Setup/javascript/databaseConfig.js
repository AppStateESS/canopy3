const usernameInput = document.getElementById('username-input')
const passwordInput = document.getElementById('password-input')
const databaseInput = document.getElementById('database-input')
const hostInput = document.getElementById('host-input')
const portInput = document.getElementById('port-input')
const driverInput = document.getElementById('driver-input')

const usernameConfig = document.getElementById('username-config')
const passwordConfig = document.getElementById('password-config')
const databaseConfig = document.getElementById('database-config')
const hostConfig = document.getElementById('host-config')
const portConfig = document.getElementById('port-config')
const driverConfig = document.getElementById('driver-config')

const testConnection = document.getElementById('test-connection')

hostConfig.innerText = 'localhost'
driverConfig.innerText = driverInput.selectedOptions[0].value

usernameInput.addEventListener('keyup', () => {
  usernameConfig.innerText = usernameInput.value
})
passwordInput.addEventListener('keyup', () => {
  passwordConfig.innerText = passwordInput.value
})
databaseInput.addEventListener('keyup', () => {
  databaseConfig.innerText = databaseInput.value
})
hostInput.addEventListener('keyup', () => {
  hostConfig.innerText = hostInput.value
})
portInput.addEventListener('keyup', () => {
  portConfig.innerText = portInput.value
})
driverInput.addEventListener('change', () => {
  driverConfig.innerText = driverInput.value
})

testConnection.addEventListener('click', () => {
  connect()
})

const httpRequest = new XMLHttpRequest()
const connect = () => {
  if (!httpRequest) {
    alert('Giving up :( Cannot create an XMLHTTP instance')
    return false
  }
  httpRequest.onreadystatechange = testResult
  httpRequest.open('GET', './d/Setup/Setup/dbTest')
  httpRequest.setRequestHeader('X-Requested-With', 'XMLHttpRequest')
  httpRequest.send()
}

const testResult = () => {
  if (httpRequest.readyState === XMLHttpRequest.DONE) {
    if (httpRequest.status === 200) {
      console.log(httpRequest.responseText)
    } else {
      alert('There was a problem with the request.')
    }
  }
}
