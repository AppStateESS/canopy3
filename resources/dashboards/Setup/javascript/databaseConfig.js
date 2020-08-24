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

const usernameError = document.getElementById('username-error')
const databaseError = document.getElementById('database-error')

const testConnection = document.getElementById('test-connection')
const saveButton = document.getElementById('save-button')

hostConfig.innerText = 'localhost'
driverConfig.innerText = driverInput.selectedOptions[0].value

usernameInput.addEventListener('keyup', () => {
  usernameConfig.innerText = usernameInput.value
})

usernameInput.addEventListener('blur', () => {
  testFields()
})

databaseInput.addEventListener('blur', () => {
  testFields()
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

const testFields = () => {
  if (usernameInput.value.length == 0 || databaseInput.value.length == 0) {
    testConnection.disabled = true
    saveButton.disabled = true
  } else {
    testConnection.disabled = false
    saveButton.disabled = false
  }
}

const httpRequest = new XMLHttpRequest()
const connect = () => {
  if (!httpRequest) {
    alert('Giving up :( Cannot create an XMLHTTP instance')
    return false
  }

  let requestUrl = new URL(window.location.href + 'd/Setup/Setup/dbTest')
  requestUrl.searchParams.set('username', usernameInput.value)
  requestUrl.searchParams.set('password', passwordInput.value)
  requestUrl.searchParams.set('dbname', databaseInput.value)
  requestUrl.searchParams.set('host', hostInput.value)
  requestUrl.searchParams.set('port', portInput.value)
  requestUrl.searchParams.set('driver', driverInput.value)

  httpRequest.onload = () => {
    if (httpRequest.status == 200) {
      parseResponse(httpRequest.response)
    } else {
      console.error('Error!')
    }
  }

  httpRequest.open('GET', requestUrl)
  httpRequest.responseType = 'json'
  httpRequest.setRequestHeader('X-Requested-With', 'XMLHttpRequest')
  httpRequest.send()
}

const parseResponse = (response) => {
  if (response.success) {
    alert('parseResponse successful')
  } else {
    if (response.error.databaseNameEmpty !== undefined) {
      databaseError.style['display'] = 'block'
      databaseError.innerHTML = 'Error: Database name empty'
    } else {
      databaseError.style['display'] = 'none'
    }

    if (response.error.userNameEmpty !== undefined) {
      usernameError.style['display'] = 'block'
      usernameError.innerHTML = 'Error: User name empty'
    } else {
      usernameError.style['display'] = 'none'
    }
  }
}
