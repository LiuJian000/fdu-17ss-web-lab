let container = document.getElementsByClassName("flex-container justify")[0];

for (let k=0;k<4;k++){
    outPutCountryBox(countries[k].name,countries[k].continent,countries[k].cities,countries[k].photos);
}

function outPutCountryBox(name,continent,cities,photos) {

    let outer_box=document.createElement("div");
    outer_box.className="item";
    let name0=document.createElement("h2");
    let name0_content=document.createTextNode(name);
    name0.appendChild(name0_content);
    let continent0=document.createElement("p");
    let continent0_content=document.createTextNode(continent);
    continent0.appendChild(continent0_content);
    outer_box.appendChild(name0);
    outer_box.appendChild(continent0);

    let city_box=document.createElement("div");
    city_box.className="inner-box";
    let city_title=document.createElement("h3");
    let city_title_content=document.createTextNode("City");
    city_title.appendChild(city_title_content);
    city_box.appendChild(city_title);
    city_box.appendChild(outputCities(cities));
    outer_box.appendChild(city_box);

    let photo_outer_box=document.createElement("div");
    photo_outer_box.className="inner-box";
    let photo_title=document.createElement("h3");
    let photo_title_content=document.createTextNode("Popular Photos");
    photo_title.appendChild(photo_title_content);
    photo_outer_box.appendChild(photo_title);
    photo_outer_box.appendChild(outputPhotos(photos));
    outer_box.appendChild(photo_outer_box);

    let visit_button=document.createElement("button");
    let visit_button_title=document.createTextNode("Visit");
    visit_button.appendChild(visit_button_title);
    outer_box.appendChild(visit_button);

    container.appendChild(outer_box);
}

function outputCities(cities) {
    let ulList=document.createElement("ul");
    for (let j=0;j<cities.length;j++ ){
        let li0=document.createElement("li");
        let li0_content=document.createTextNode(cities[j]);
        li0.appendChild(li0_content);
        ulList.appendChild(li0);
    }
    return ulList;
}
function outputPhotos(photos) {
    let photo_inner_box=document.createElement("div");
    for (let i=0;i<photos.length;i++){
        let img=document.createElement("img");
        img.src="images/"+photos[i];
        img.className="photo";
        photo_inner_box.appendChild(img);
    }
    return photo_inner_box;
}