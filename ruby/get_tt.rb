def get_tt(location)
  require 'json'
  case location
  when "kutc"
    kutc_tt = open("../tt/kutc.json") do |io|
      JSON.load(io)
    end
  when "takatsuki"
    takatsuki_tt = open("../tt/takatsuki.json") do |io|
      JSON.load(io)
    end
  when "tonda"
    tonda_tt = open("../tt/tonda.json") do |io|
      JSON.load(io)
    end
  end
end
