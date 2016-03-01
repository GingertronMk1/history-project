api_key = "fCXo05bxAfCoY31I5vtcmPr8AEY3uQTr"

fetch_sm = (url, callback) ->
  $.ajax
    url: "https://www.smugmug.com/api/v2/#{ url }?APIKey=#{ api_key }&count=5000"
    method: "GET"
    contentType: "application/json; charset=utf-8",
    dataType: "json",
    success: callback
    error: (err) ->
      alert "Error: #{err}"

fill_album_list = (albums) ->
  ret = $("#smug-albums").html()
  $(albums).each (i, album) ->
    ret += "<tr><td>#{ i+1 }</td><td>#{ album["Name"] }</td><td>#{ album["AlbumKey"] }</td><td>#{ album["ImageCount"] }</tr>\n"
  $("#smug-albums").html(ret)

fill_image_list = (images) ->
  ret = $("#smug-images").html()
  $(images).each (i, image) ->
    ret += "<tr><td>#{ i+1 }</td><td><img src=\"#{ image["ThumbnailUrl"] }\" alt=\"Thumb\"/><td>#{ image["Title"] }</td><td>#{ image["FileName"] }</td><td>#{ image["ImageKey"] }</tr>\n"
  $("#smug-images").html(ret)

$(document).ready ->
  if $('body').hasClass 'util-smug-albums'
    fetch_sm "user/newtheatre!albums", (data) ->
      window.d = data
      fill_album_list(data["Response"]["Album"])
  if $('body').hasClass 'util-smug-album'
    key = $('#smug-images').data("album")
    fetch_sm "album/#{ key }!images", (data) ->
      window.d = data
      fill_image_list(data["Response"]["AlbumImage"])
