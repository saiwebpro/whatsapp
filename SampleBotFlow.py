from flask import Flask, request, jsonify
from ResponseChat import ResponseChat, Menu, ButtonObject
import logging
import json

app = Flask(__name__)

@app.route('/', methods=['POST'])
def handle_request():
    r = ResponseChat()
    logging.basicConfig(filename='chat_builder.log', level=logging.INFO)
    logging.info('##################WHATSAPP REQUEST#####################')

    json_data = request.get_json()
    logging.info('Data: {}'.format(json.dumps(json_data)))

    if json_data["data"]["type"] == "text":
        
        # Create a ButtonObject
        buttons = ButtonObject("Please share your feedback on this document")
        buttons.add_button("1", "Send Text")
        buttons.add_button("2", "Send Video")
        buttons.add_button("3", "Send List")
        # Add the buttons to the response
        r.add_buttons(buttons)
        r.set_bot_state("Next_Menu")

    elif (json_data["data"]["type"] == "reply" and json_data["bot_state"] == "Next_Menu"):
        reply_id = json_data["data"]["body"]["id"]
        if reply_id == "1":
            r.send_text("We will contact you soon")
        elif reply_id == "2":
            r.send_video("https://media.w3.org/2010/05/sintel/trailer.mp4", caption="Sample Video")
        elif reply_id == "3":
            list_obj = r.init_interactive("Welcome to the Ozonetel Cloud Communication API")
            section = list_obj.section("Sending Examples")
            section.add_choice("image", "Sending Image", description="sample video")
            section.add_choice("document", "Sending Document", description="document ")
            section.add_choice("location", "Sending Location", description="location")
            r.add_interactive_object(list_obj)
            r.set_bot_state("Other_Menu")
        else:
            r.send_text("Unknown event")

    elif (json_data["data"]["type"] == "reply" and json_data["data"]["body"]["id"]=="document"):
        r.send_document("https://www.africau.edu/images/default/sample.pdf", filename="Sample Document.pdf")

    elif (json_data["data"]["type"] == "reply" and json_data["data"]["body"]["id"]=="image"):
        r.send_image("https://fastly.picsum.photos/id/166/536/354.jpg?hmac=RVMya5ENxTk2PBcj7FgUBOwHIkbN78Dl5YmGmvFasOY", caption="Sample Image")
    elif (json_data["data"]["type"] == "reply" and json_data["data"]["body"]["id"]=="location"):
        r.send_location("37.7749", "-122.4194", "Ozonetel HQ", "San Francisco, CA")
    else:
        r.send_text("Invalid Request data")

    logging.info('Return: {}'.format(r.get_data()))
    return jsonify(r.get_response())

if __name__ == '__main__':
    app.run(debug=True)
