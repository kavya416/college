const inputText = document.querySelector("textarea");
const tagsDiv = document.querySelector(".tags")
inputText.addEventListener("keyup",function(e) {
    createTags(e.target.value)

    if(e.key === "Enter")
    {
        setTimeout(()=>
        {
            e.target.value = ''
        },10)
        randomSelection()
    }
})
function createTags(word) {
    const tags = word.split(',').filter(tag => tag.trim() !== '').map(tag => tag.trim())
    tagsDiv.innerHTML = ''

    tags.forEach(tag => {
        const tagElement = document.createElement('span')
        tagElement.classList.add('tag')
        tagElement.innerText = tag
        tagsDiv.appendChild(tagElement)
    })
}
function randomSelection() 
{
    const times = 30;
    const interval = setInterval(()=>{
        const randomTag = pickRandomTag();
        addHighlightTag(randomTag);
        setTimeout(()=>{
removeHighlightTag(randomTag);
        },100)
    },100)
    setTimeout(()=>{
clearInterval(interval);
setTimeout(()=>{
    const randomTag = pickRandomTag();
    addHighlightTag(randomTag);

},100)
    }, times * 100)
}
function pickRandomTag()
{
    const tags = document.querySelectorAll(".tag");
    return tags[Math.floor(Math.random() * tags.length)]
}
function addHighlightTag(tag)
{
    tag.classList.add('highlight')

}
function removeHighlightTag(tag)
{
    tag.classList.remove('highlight')

}