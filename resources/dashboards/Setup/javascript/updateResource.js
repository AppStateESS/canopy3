let resourceUrl = '/sample/dir/'
const resourceUrlNode = document.getElementById('resourceUrl')
const saveUrlNode = document.getElementById('saveUrl')
console.log(saveUrlNode)
saveUrlNode.innerText = resourceUrl
resourceUrlNode.addEventListener('keyup', (e) => {
  saveUrlNode.innerText = resourceUrlNode.value
})
