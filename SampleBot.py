from ResponseChat import ButtonObject, ListObject, ResponseChat

def handle_menu_choice(choice):
    resp_id = choice["id"]
    if resp_id == "text":
        response.send_text("Welcome to the Ozonetel Cloud Communication API")
    elif resp_id == "image":
        response.send_image("https://example.com/sample_image.jpg", caption="Sample Image")
    elif resp_id == "video":
        response.send_video("https://example.com/sample_video.mp4", caption="Sample Video")
    elif resp_id == "document":
        response.send_document("https://example.com/sample_document.pdf", filename="Sample Document.pdf")
    elif resp_id == "location":
        response.send_location("37.7749", "-122.4194", "Ozonetel HQ", "San Francisco, CA")
    elif resp_id == "buttons":
        # Create a ButtonObject
        buttons = ButtonObject("Please share your feedback on this document")
        buttons.add_button("3", "Excellent")
        buttons.add_button("2", "Good")
        buttons.add_button("1", "Poor")
        # Add the buttons to the response
        response.add_buttons(buttons)
    elif resp_id == "list":
        list_obj = response.init_interactive("Welcome to the Ozonetel Cloud Communication API")
        section = list_obj.section("Sending Examples")
        section.add_choice("text", "Sending Text", description="It shares a sample JSON object and sample image")
        section.add_choice("image", "Sending Image", description="It shares a sample JSON object and sample image")
        section.add_choice("video", "Sending Video", description="It shares a sample JSON object and sample video")
        section.add_choice("document", "Sending Document", description="It shares a sample JSON document object")
        section.add_choice("location", "Sending Location", description="It shares a sample JSON location object")
        section.add_choice("buttons", "Interactive Buttons", description="Get a JSON interactive buttons object")
        section.add_choice("list", "List Example", description="Interactive List")
        response.add_interactive_object(list_obj)

response = ResponseChat()

menu_choices = [
    {"id": "text", "title": "Sending Text"},
    {"id": "image", "title": "Sending Image"},
    {"id": "video", "title": "Sending Video"},
    {"id": "document", "title": "Sending Document"},
    {"id": "location", "title": "Sending Location"},
    {"id": "buttons", "title": "Interactive Buttons"},
    {"id": "list", "title": "List Example"}
]

list_obj = response.init_interactive("Welcome to the Ozonetel Cloud Communication API")
section = list_obj.section("Sending Examples")

for choice in menu_choices:
    section.add_choice(choice["id"], choice["title"])

list_obj.section(section)
response.set_bot_state("MainMenu")

choice_id = input("Enter the choice ID: ")
menu_choice = next((choice for choice in menu_choices if choice["id"] == choice_id), None)

if menu_choice:
    handle_menu_choice(menu_choice)
else:
    print("Invalid choice.")

response.send()
