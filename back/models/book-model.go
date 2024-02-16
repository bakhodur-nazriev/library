package models

import "time"

type Book struct {
	ID          int        `json:"id"`
	Name        string     `json:"name"`
	Description string     `json:"description"`
	ISBN        string     `json:"isbn"`
	Pages       string     `json:"pages"`
	Publisher   string     `json:"publisher"`
	PublishDate *time.Time `json:"publish_date"`
	AddDate     *time.Time `json:"add_date"`
	LanguageId  int        `json:"language_id"`
}
