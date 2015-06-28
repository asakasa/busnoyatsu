def get_tt(location)
  require 'json'
  require 'rubygems'

  tt_file = open("../tt/#{location}.json")
  location = tt_file.read
  parsed = JSON.parse(location)
end
