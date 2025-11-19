var computed = false;
var decimal = 0;

function convert (entryform, form, to)
{
    convertfrom = form.selectedIndex;
    convertto = to.selectedIndex;
    entryform.display.value = (entryform.input.value * from[convertfrom].value / to[convertto].value);
}

function addChar (input, character)
{
    if((character=='.' && decimal=="0") || character!='.')
    {
        (input.value == "" || input.value == "0") ? input.value = character : input.value += character
        convert(input.form,input.form.measure1,input.form.measure2)
        computed = true;
        if (character=='.')
        {
            decimal = 1;
        }
    }
}

function openvothcom()
{
    window.open("","Display window","toolbar=no,directories=no,menubar=no");
}

function clear (form)
{
    form.input.value = 0;
    form.display.value = 0;
    decimal=0;
}

function brightenColor(hexInput) {

    var hexStr = hexInput.slice(1);

    var pairs = [hexStr.slice(0, 2), hexStr.slice(2, 4), hexStr.slice(4, 6)];

    var modifiedPairs = [];
    for (var i = 0; i < pairs.length; i++) {
        var value = parseInt(pairs[i], 16);
        value = value * 2;
        if (value > 255) value = 255;
        var hexValue = value.toString(16).padStart(2, '0');
        modifiedPairs.push(hexValue);
    }

  return `#${modifiedPairs.join('')}`;
}


function changeBackground(hexNumber)
{
    
  var lighter = brightenColor(hexNumber)

  document.body.style.backgroundColor = hexNumber;
  document.querySelector(".site-header").style.background = `linear-gradient(180deg, ${hexNumber} 0%, ${lighter} 100%)`;
}