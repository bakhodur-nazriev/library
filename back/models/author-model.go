package models

import "time"

type Author struct {
	ID          int        `json:"id"`
	FullName    string     `json:"full_name"`
	BirthDate   *time.Time `json:"birth_date"`
	Biography   string     `json:"biography"`
	Nationality string     `json:"nationality"`
}
