import json
from time import time

class ResponseChat:
    def __init__(self, loggerFileName=None):
        self.data = {"bot_state": str(time()), "data": []}
        self.logger = Logger(loggerFileName) if loggerFileName else None

    def set_bot_state(self, state=""):
        self.data['bot_state'] = state

    def send_text(self, msg=""):
        self.logger.debug(locals()) if self.logger else None
        self.data['data'].append({"type": "text", "body": {"data": msg}})

    def send_document(self, link="", filename="", mimeType="", caption=""):
        self.logger.debug(locals()) if self.logger else None
        self.data['data'].append({"type": "document", "body": {"link": link, "filename": filename, "mime_type": mimeType, "caption": caption}})

    def send_image(self, link="", mimeType="", caption=""):
        self.data['data'].append({"type": "image", "body": {"link": link, "mime_type": mimeType, "caption": caption}})

    def send_audio(self, link="", mimeType="", caption=""):
        self.data['data'].append({"type": "audio", "body": {"link": link, "mime_type": mimeType, "caption": caption}})

    def send_location(self, latitude="", longitude="", name="", address=""):
        self.data['data'].append({"type": "location", "body": {"latitude": latitude, "longitude": longitude, "name": name, "address": address}})

    def send_video(self, link="", mimeType="", caption=""):
        self.data['data'].append({"type": "video", "body": {"link": link, "mime_type": mimeType, "caption": caption}})

    def add_contact(self, contact_object):
        self.data['data'].append({"type": "Contact", "Contact": contact_object})

    def send_agent_transfer(self, skillName="", uui=""):
        self.logger.debug(locals()) if self.logger else None
        self.data['data'].append({"type": "CCTransfer", "skillName": skillName, "uui": uui})

    def init_interactive(self, name):
        return ListObject(name)

    def add_interactive_object(self, list_object):
        self.logger.debug(locals()) if self.logger else None
        self.data['data'].append({"type": "interactive", "body": list_object.build_object()})

    def add_buttons(self, list_object):
        self.logger.debug(locals()) if self.logger else None
        self.data['data'].append({"type": "interactive", "body": list_object.build_object()})

    def get_data(self):
        return json.dumps(self.data)

    def get_response(self):
        return self.data

    def put_test_data(self, data):
        self.data = data

    def get_xml(self):
        # Code for generating XML representation of data
        pass

    def print_data(self):
        return json.dumps(self.data)

    def send(self):
        self.logger.info("whatsapp post data:::" + json.dumps(self.data, indent=4)) if self.logger else None
        print(self.get_data())


class Menu:
    def __init__(self, type, title, link):
        self.type = type
        self.title = title
        self.link = link
        self.buttons = []

    def add_choice(self, id, title):
        self.buttons.append({"id": id, "title": title})

    def return_menu_payload(self):
        payload = {
            "type": self.type,
            "title": self.title,
            "link": self.link,
            "choices": self.buttons
        }
        return payload

    def return_button_payload_image(self, image):
        payload = {
            "type": self.type,
            self.type: {
                "type": "button",
                "header": {
                    "type": "image",
                    "image": {
                        "link": image
                    }
                },
                "body": {
                    "text": self.title
                },
                "action": {
                    "buttons": self.buttons
                }
            }
        }
        self.buttons = []
        return payload


class ButtonObject:
    def __init__(self, title):
        self.body = title
        self.buttons = []

    def add_button(self, id, title):
        self.buttons.append({"id": id, "title": title})

    def build_object(self):
        payload = {
            "type": "button",
            "title": self.body,
            "choices": self.buttons
        }
        return payload


class ListObject:
    def __init__(self, title):
        self.body = title
        self.sections = []

    def section(self, title):
        section = ListObjectSection(title)
        self.sections.append(section)
        return section

    def build_object(self):
        payload = {
            "type": "list",
            "title": self.body,
            "sections": [section.build_object() for section in self.sections]
        }
        return payload


class ListObjectSection:
    def __init__(self, title):
        self.title = title
        self.choices = []

    def add_choice(self, id, title, description=""):
        self.choices.append({"id": id, "title": title, "description": description})

    def build_object(self):
        payload = {
            "title": self.title,
            "choices": self.choices
        }
        return payload