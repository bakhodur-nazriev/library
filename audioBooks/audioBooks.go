package audioBooks

import fiber2 "github.com/gofiber/fiber/v2"

func GetAudioBooks(c *fiber2.Ctx) error {
	c.SendString("A Audio Books")
	return nil
}

func GetAudioBook(c *fiber2.Ctx) error {
	c.SendString("A single audio book")
	return nil
}

func NewAudioBook(c *fiber2.Ctx) error {
	c.SendString("A new audio book")
	return nil
}

func UpdateAudioBook(c *fiber2.Ctx) error {
	c.SendString("Update book")
	return nil
}

func DeleteAudioBook(c *fiber2.Ctx) error {
	c.SendString("Delete Audio Book")
	return nil
}
